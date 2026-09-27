<?php

namespace App\Http\Controllers\Ppdb;

use App\Events\PendaftarLulusEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ppdb\PeriodePpdbRequest;
use App\Http\Requests\Ppdb\TetapkanKelulusanRequest;
use App\Http\Requests\Ppdb\VerifikasiBerkasRequest;
use App\Models\BerkasPendaftaran;
use App\Models\HasilSeleksi;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Services\SyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminPpdbController extends Controller
{
    /**
     * 1. Manajemen Periode PPDB
     */
    public function periodeIndex(): View
    {
        $periodeList = PeriodePpdb::withCount('pendaftar')
            ->orderByDesc('is_aktif')
            ->orderByDesc('tanggal_buka')
            ->get();

        return view('admin.ppdb.periode', compact('periodeList'));
    }

    /**
     * Menyimpan atau memperbarui periode PPDB.
     */
    public function simpanPeriode(PeriodePpdbRequest $request, ?PeriodePpdb $periode = null): RedirectResponse
    {
        $validated = $request->validated();
        $isAktif = $request->boolean('is_aktif');

        DB::transaction(function () use ($validated, $isAktif, $periode) {
            if ($isAktif) {
                // Nonaktifkan periode lain jika periode ini dijadikan aktif
                PeriodePpdb::where('is_aktif', true)->update(['is_aktif' => false]);
            }

            if ($periode && $periode->exists) {
                $periode->update(array_merge($validated, ['is_aktif' => $isAktif]));
            } else {
                PeriodePpdb::create(array_merge($validated, ['is_aktif' => $isAktif]));
            }
        });

        return back()->with('success', 'Data periode PPDB berhasil disimpan.');
    }

    /**
     * Mengaktifkan atau menonaktifkan periode secara cepat.
     */
    public function togglePeriode(PeriodePpdb $periode): RedirectResponse
    {
        if (!$periode->is_aktif) {
            PeriodePpdb::where('is_aktif', true)->update(['is_aktif' => false]);
            $periode->update(['is_aktif' => true]);
        } else {
            $periode->update(['is_aktif' => false]);
        }

        return back()->with('success', 'Status periode pendaftaran berhasil diperbarui.');
    }

    /**
     * 2. Manajemen & Filter Pendaftar PPDB
     */
    public function pendaftarIndex(Request $request): View
    {
        $query = Pendaftar::with(['periode', 'berkas', 'hasilSeleksi']);

        // Filter Pencarian
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$keyword}%")
                    ->orWhere('asal_sekolah', 'like', "%{$keyword}%");
            });
        }

        // Filter Status Pendaftaran
        if ($request->filled('status') && $request->input('status') !== 'semua') {
            $query->where('status_pendaftaran', $request->input('status'));
        }

        // Filter Periode
        if ($request->filled('periode_id') && $request->input('periode_id') !== 'semua') {
            $query->where('periode_id', $request->input('periode_id'));
        }

        $pendaftarList = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        // Statistik
        $totalPendaftar = Pendaftar::count();
        $menungguVerifikasi = Pendaftar::where('status_pendaftaran', 'menunggu_verifikasi')->count();
        $terverifikasi = Pendaftar::where('status_pendaftaran', 'terverifikasi')->count();
        $lulus = Pendaftar::where('status_pendaftaran', 'lulus')->count();

        $daftarPeriode = PeriodePpdb::orderByDesc('tanggal_buka')->get();

        return view('admin.ppdb.pendaftar', compact(
            'pendaftarList',
            'totalPendaftar',
            'menungguVerifikasi',
            'terverifikasi',
            'lulus',
            'daftarPeriode'
        ));
    }

    /**
     * 3. Verifikasi Berkas Calon Siswa (Halaman Rinci)
     */
    public function showVerifikasi(Pendaftar $pendaftar): View
    {
        $pendaftar->load(['periode', 'orangTua', 'berkas', 'hasilSeleksi']);
        $berkasList = $pendaftar->berkas->keyBy('jenis_berkas');

        $dokumenSyarat = [
            'kartu_keluarga' => 'Kartu Keluarga (KK)',
            'akta_kelahiran' => 'Akta Kelahiran',
            'ijazah_skl' => 'Ijazah / SKL SMP',
            'rapor' => 'Buku Rapor Semester 1–5',
        ];

        return view('admin.ppdb.verifikasi', compact('pendaftar', 'berkasList', 'dokumenSyarat'));
    }

    /**
     * Verifikasi individual berkas digital.
     */
    public function verifikasiBerkas(
        VerifikasiBerkasRequest $request,
        Pendaftar $pendaftar,
        BerkasPendaftaran $berkas
    ): RedirectResponse {
        $validated = $request->validated();

        $berkas->update([
            'status_verifikasi' => $validated['status_verifikasi'],
            'catatan' => $validated['catatan'] ?? null,
            'diverifikasi_oleh' => Auth::id(),
            'diverifikasi_pada' => now(),
        ]);

        return back()->with('success', 'Status berkas ' . str_replace('_', ' ', $berkas->jenis_berkas) . ' berhasil diperbarui.');
    }

    /**
     * Finalisasi status verifikasi pendaftar secara menyeluruh.
     */
    public function finalisasiVerifikasi(Request $request, Pendaftar $pendaftar): RedirectResponse
    {
        $status = $request->input('status'); // 'terverifikasi' atau 'draft'

        if ($status === 'terverifikasi') {
            // Pastikan seluruh berkas wajib berstatus valid
            $invalidCount = $pendaftar->berkas()->where('status_verifikasi', '!=', 'valid')->count();
            $totalCount = $pendaftar->berkas()->count();

            if ($totalCount < 4 || $invalidCount > 0) {
                return back()->with('error', 'Semua 4 dokumen persyaratan wajib berstatus VALID sebelum menetapkan status terverifikasi.');
            }

            $pendaftar->update(['status_pendaftaran' => 'terverifikasi']);
            return back()->with('success', 'Berkas pendaftar berhasil ditetapkan TERVERIFIKASI.');
        } else {
            $pendaftar->update(['status_pendaftaran' => 'draft']);
            return back()->with('success', 'Status pendaftar dikembalikan ke DRAFT untuk perbaikan oleh siswa.');
        }
    }

    /**
     * 4. Halaman Penetapan Kelulusan Peserta Seleksi
     */
    public function seleksiIndex(Request $request): View
    {
        $query = Pendaftar::with(['periode', 'hasilSeleksi'])
            ->whereIn('status_pendaftaran', ['terverifikasi', 'lulus', 'tidak_lulus']);

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status_seleksi') && $request->input('status_seleksi') !== 'semua') {
            $status = $request->input('status_seleksi');
            $query->whereHas('hasilSeleksi', fn($q) => $q->where('status', $status));
        }

        $pendaftarSeleksi = $query->paginate(15)->withQueryString();

        return view('admin.ppdb.seleksi', compact('pendaftarSeleksi'));
    }

    /**
     * Menyimpan penetapan kelulusan calon siswa.
     */
    public function tetapkanKelulusan(
        TetapkanKelulusanRequest $request,
        Pendaftar $pendaftar,
        SyncService $syncService
    ): RedirectResponse {
        $validated = $request->validated();
        $status = $validated['status']; // LULUS / TIDAK_LULUS

        DB::transaction(function () use ($pendaftar, $validated, $status) {
            HasilSeleksi::updateOrCreate(
                ['pendaftar_id' => $pendaftar->id],
                [
                    'status' => $status,
                    'catatan' => $validated['catatan'] ?? null,
                    'diverifikasi_oleh' => Auth::id(),
                    'tanggal_pengumuman' => now(),
                ]
            );

            $pendaftar->update([
                'status_pendaftaran' => strtolower($status),
            ]);
        });

        // Trigger sinkronisasi otomatis PPDB -> SIAKAD saat dinyatakan LULUS
        $syncMsg = '';
        if ($status === 'LULUS') {
            $syncResult = $syncService->syncPendaftar($pendaftar);
            PendaftarLulusEvent::dispatch($pendaftar);
            $syncMsg = $syncResult['success'] ? ' Data berhasil disinkronkan ke SIAKAD.' : ' Namun sinkronisasi SIAKAD tertunda: ' . $syncResult['message'];
        }

        return back()->with('success', "Status kelulusan {$pendaftar->nama_lengkap} berhasil ditetapkan menjadi {$status}.{$syncMsg}");
    }
}
