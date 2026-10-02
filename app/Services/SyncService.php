<?php

namespace App\Services;

use App\Models\Pendaftar;
use App\Models\Siswa;
use App\Models\SyncLog;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncService
{
    /**
     * Menyinkronkan satu data calon siswa lulus ke entitas Siswa SIAKAD.
     * Idempotent: jika siswa sudah ada, data diperbarui tanpa duplikasi.
     */
    public function syncPendaftar(Pendaftar $pendaftar): array
    {
        // Pastikan calon siswa berstatus LULUS / DITERIMA
        $statusPendaftar = strtolower($pendaftar->status_pendaftaran);
        $statusHasil = $pendaftar->hasilSeleksi ? strtoupper($pendaftar->hasilSeleksi->status) : '';
        $isLulus = in_array($statusPendaftar, ['lulus', 'diterima']) ||
            in_array($statusHasil, ['LULUS', 'DITERIMA']);

        if (!$isLulus) {
            return [
                'success' => false,
                'message' => "Pendaftar {$pendaftar->nama_lengkap} ({$pendaftar->no_pendaftaran}) belum dinyatakan DITERIMA / LULUS.",
            ];
        }

        try {
            return DB::transaction(function () use ($pendaftar) {
                // 1. Tentukan tahun masuk & generate NIS unik jika belum ada
                $tahunMasuk = substr($pendaftar->periode?->tahun_ajaran ?? date('Y'), 0, 4);

                $existingSiswa = Siswa::where('nisn', $pendaftar->nisn)
                    ->orWhere('user_id', $pendaftar->user_id)
                    ->first();

                if ($existingSiswa && $existingSiswa->nis) {
                    $nis = $existingSiswa->nis;
                } else {
                    $urutan = Siswa::where('tahun_masuk', $tahunMasuk)->count() + 1;
                    $nis = $tahunMasuk . str_pad($urutan, 4, '0', STR_PAD_LEFT);
                }

                // 2. Buat atau perbarui entitas Siswa di SIAKAD
                $siswa = Siswa::updateOrCreate(
                    ['nisn' => $pendaftar->nisn],
                    [
                        'user_id' => $pendaftar->user_id,
                        'nis' => $nis,
                        'nama' => $pendaftar->nama_lengkap,
                        'jenis_kelamin' => $pendaftar->jenis_kelamin,
                        'alamat' => $pendaftar->alamat,
                        'tahun_masuk' => $tahunMasuk,
                        'status_aktif' => true,
                    ]
                );

                // 3. Upgrade peran akun SSO dari 'calon_siswa' menjadi 'siswa'
                $user = User::find($pendaftar->user_id);
                if ($user && $user->role === 'calon_siswa') {
                    $user->update(['role' => 'siswa']);
                }

                // 4. Catat riwayat audit di tabel sync_log
                $payload = [
                    'no_pendaftaran' => $pendaftar->no_pendaftaran,
                    'nisn' => $pendaftar->nisn,
                    'nis' => $siswa->nis,
                    'nama' => $siswa->nama,
                    'tahun_masuk' => $siswa->tahun_masuk,
                    'asal_sekolah' => $pendaftar->asal_sekolah,
                    'nama_ayah' => $pendaftar->orangTua?->nama_ayah,
                    'nama_ibu' => $pendaftar->orangTua?->nama_ibu,
                    'synced_at' => now()->toIso8601String(),
                ];

                $log = SyncLog::create([
                    'pendaftar_id' => $pendaftar->id,
                    'siswa_id' => $siswa->id,
                    'user_id' => $pendaftar->user_id,
                    'waktu_sinkron' => now(),
                    'status' => 'BERHASIL',
                    'detail_payload' => $payload,
                    'error_message' => null,
                ]);

                return [
                    'success' => true,
                    'siswa' => $siswa,
                    'log' => $log,
                    'message' => "Data siswa {$siswa->nama} (NISN: {$siswa->nisn}, NIS: {$siswa->nis}) berhasil disinkronkan ke SIAKAD.",
                ];
            });
        } catch (Exception $e) {
            Log::error("Gagal menyinkronkan pendaftar ID {$pendaftar->id}: " . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Catat kegagalan transaksi di sync_log
            SyncLog::create([
                'pendaftar_id' => $pendaftar->id,
                'siswa_id' => null,
                'user_id' => $pendaftar->user_id,
                'waktu_sinkron' => now(),
                'status' => 'GAGAL',
                'detail_payload' => ['error' => $e->getMessage()],
                'error_message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => "Terjadi kesalahan saat sinkronisasi: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Menjalankan sinkronisasi massal (Batch Sync) untuk seluruh pendaftar lulus.
     */
    public function syncBatch(?int $periodeId = null): array
    {
        $query = Pendaftar::with(['periode', 'orangTua', 'hasilSeleksi'])
            ->where(function ($q) {
                $q->whereIn('status_pendaftaran', ['lulus', 'diterima'])
                    ->orWhereHas('hasilSeleksi', fn($h) => $h->whereIn('status', ['LULUS', 'DITERIMA']));
            });

        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        $pendaftarLulus = $query->get();

        $berhasil = 0;
        $gagal = 0;
        $messages = [];

        foreach ($pendaftarLulus as $pendaftar) {
            $result = $this->syncPendaftar($pendaftar);
            if ($result['success']) {
                $berhasil++;
            } else {
                $gagal++;
            }
            $messages[] = $result['message'];
        }

        return [
            'total' => $pendaftarLulus->count(),
            'berhasil' => $berhasil,
            'gagal' => $gagal,
            'messages' => $messages,
        ];
    }

    /**
     * Mencoba ulang (Retry) sinkronisasi untuk log yang gagal.
     */
    public function retrySync(SyncLog $log): array
    {
        $pendaftar = $log->pendaftar;
        if (!$pendaftar) {
            return [
                'success' => false,
                'message' => 'Data pendaftar terkait tidak ditemukan.',
            ];
        }

        return $this->syncPendaftar($pendaftar);
    }
}
