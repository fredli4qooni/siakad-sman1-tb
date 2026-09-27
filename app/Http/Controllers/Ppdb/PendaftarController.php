<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ppdb\SimpanPendaftarRequest;
use App\Models\OrangTua;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PendaftarController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran calon siswa.
     */
    public function formulir(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar()->with('orangTua')->first();

        // Jika calon siswa belum memiliki entitas pendaftar, buat draft pendaftar awal
        if (!$pendaftar) {
            $periodeAktif = PeriodePpdb::where('is_aktif', true)->first();
            if (!$periodeAktif) {
                return redirect()->route('pendaftar.dashboard')->with('error', 'Tidak ada periode PPDB aktif.');
            }

            $nomorUrut = Pendaftar::where('periode_id', $periodeAktif->id)->count() + 1;
            $noPendaftaran = 'PPDB-' . date('Y') . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

            $pendaftar = Pendaftar::create([
                'user_id' => $user->id,
                'periode_id' => $periodeAktif->id,
                'no_pendaftaran' => $noPendaftaran,
                'nisn' => '0000000000',
                'nama_lengkap' => $user->nama,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => '-',
                'tanggal_lahir' => now()->subYears(15)->format('Y-m-d'),
                'agama' => 'Islam',
                'asal_sekolah' => '-',
                'alamat' => '-',
                'no_hp' => '-',
                'status_pendaftaran' => 'draft',
            ]);
        }

        $orangTua = $pendaftar->orangTua ?? new OrangTua();

        return view('ppdb.pendaftar.formulir', compact('pendaftar', 'orangTua'));
    }

    /**
     * Memproses penyimpanan data biodata calon siswa dan data orang tua.
     */
    public function simpanFormulir(SimpanPendaftarRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('pendaftar.formulir')->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek jika status pendaftaran sudah diverifikasi atau lulus
        if (in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus', 'tidak_lulus'])) {
            return redirect()->route('pendaftar.dashboard')
                ->with('error', 'Formulir telah diverifikasi atau diputuskan oleh panitia dan tidak dapat diubah lagi.');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($pendaftar, $validated) {
            // 1. Perbarui data calon siswa
            $pendaftar->update([
                'nisn' => $validated['nisn'],
                'nik' => $validated['nik'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'agama' => $validated['agama'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
            ]);

            // 2. Perbarui atau buat data orang tua
            $pendaftar->orangTua()->updateOrCreate(
                ['pendaftar_id' => $pendaftar->id],
                [
                    'nama_ayah' => $validated['nama_ayah'],
                    'pekerjaan_ayah' => $validated['pekerjaan_ayah'],
                    'nama_ibu' => $validated['nama_ibu'],
                    'pekerjaan_ibu' => $validated['pekerjaan_ibu'],
                    'nama_wali' => $validated['nama_wali'] ?? null,
                    'pekerjaan_wali' => $validated['pekerjaan_wali'] ?? null,
                    'no_hp_ortu' => $validated['no_hp_ortu'],
                ]
            );
        });

        return redirect()->route('pendaftar.berkas')
            ->with('success', 'Formulir pendaftaran berhasil disimpan! Silakan lanjutkan dengan mengunggah berkas persyaratan.');
    }
}
