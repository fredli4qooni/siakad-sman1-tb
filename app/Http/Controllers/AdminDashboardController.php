<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\Siswa;
use App\Models\SyncLog;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan ringkasan data real-time sistem SPMB & SIAKAD pada Dashboard Utama Admin.
     */
    public function index(): View
    {
        // 1. Periode PPDB / SPMB Berjalan
        $periodeAktif = PeriodePpdb::where('is_aktif', true)->first();

        // 2. Statistik SPMB Berdasarkan Database Asli
        $queryPendaftar = Pendaftar::query();
        if ($periodeAktif) {
            $queryPendaftar->where('periode_id', $periodeAktif->id);
        }

        $totalPendaftar = (clone $queryPendaftar)->count();

        // Berkas Terverifikasi (status terverifikasi atau sudah lulus/diterima seleksi)
        $terverifikasiCount = (clone $queryPendaftar)
            ->where(function ($q) {
                $q->whereIn('status_pendaftaran', ['terverifikasi', 'lulus', 'diterima'])
                    ->orWhereHas('hasilSeleksi', fn($h) => $h->whereIn('status', ['LULUS', 'DITERIMA']));
            })
            ->count();

        // Pendaftar Menunggu Tindakan / Verifikasi Berkas
        $menungguVerifikasiCount = (clone $queryPendaftar)
            ->whereIn('status_pendaftaran', ['diajukan', 'menunggu_verifikasi', 'terkirim'])
            ->whereDoesntHave('hasilSeleksi', fn($h) => $h->whereIn('status', ['LULUS', 'DITERIMA', 'TIDAK_LULUS', 'TIDAK_DITERIMA']))
            ->count();

        // Calon Siswa yang Telah Ditetapkan LULUS / DITERIMA
        $lulusCount = (clone $queryPendaftar)
            ->where(function ($q) {
                $q->whereIn('status_pendaftaran', ['lulus', 'diterima'])
                    ->orWhereHas('hasilSeleksi', fn($h) => $h->whereIn('status', ['LULUS', 'DITERIMA']));
            })
            ->count();

        $kuotaTarget = $periodeAktif ? $periodeAktif->kuota : null;

        // 3. Statistik Sinkronisasi SPMB -> SIAKAD
        $totalSync = SyncLog::count();
        $syncBerhasilCount = SyncLog::where('status', 'BERHASIL')->count();
        $syncGagalCount = SyncLog::where('status', 'GAGAL')->count();
        $persenSync = $totalSync > 0 ? round(($syncBerhasilCount / $totalSync) * 100) : 100;

        // 4. Statistik Modul SIAKAD
        $totalSiswa = Siswa::count();
        $totalSiswaAktif = Siswa::where('status_aktif', true)->count();
        $totalKelas = Kelas::count();
        $totalGuru = Guru::count();

        // 5. Data Aktivitas Pendaftaran SPMB Terbaru (6 Terakhir)
        $pendaftarTerbaru = Pendaftar::with(['periode', 'user', 'hasilSeleksi'])
            ->latest()
            ->take(6)
            ->get();

        // 6. Data Riwayat Log Sinkronisasi Otomatis Terbaru (6 Terakhir)
        $logSinkronTerbaru = SyncLog::with(['pendaftar', 'siswa'])
            ->orderByDesc('waktu_sinkron')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'periodeAktif',
            'totalPendaftar',
            'terverifikasiCount',
            'menungguVerifikasiCount',
            'lulusCount',
            'kuotaTarget',
            'totalSync',
            'syncBerhasilCount',
            'syncGagalCount',
            'persenSync',
            'totalSiswa',
            'totalSiswaAktif',
            'totalKelas',
            'totalGuru',
            'pendaftarTerbaru',
            'logSinkronTerbaru'
        ));
    }
}
