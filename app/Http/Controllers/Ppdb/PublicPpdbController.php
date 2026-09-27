<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPpdbController extends Controller
{
    /**
     * Halaman Beranda Publik PPDB & SIAKAD.
     */
    public function index(): View
    {
        $periodeAktif = PeriodePpdb::where('is_aktif', true)->first();
        $totalPendaftar = $periodeAktif ? Pendaftar::where('periode_id', $periodeAktif->id)->count() : 0;

        return view('ppdb.landing', compact('periodeAktif', 'totalPendaftar'));
    }

    /**
     * Halaman Alur Pendaftaran & Ketentuan Kuota.
     */
    public function alur(): View
    {
        $periodeAktif = PeriodePpdb::where('is_aktif', true)->first();

        return view('ppdb.alur', compact('periodeAktif'));
    }

    /**
     * Halaman Pengumuman Seleksi Publik (Cek NISN / No Pendaftaran).
     */
    public function pengumuman(Request $request): View
    {
        $keyword = trim((string) $request->input('keyword'));
        $pendaftar = null;
        $searched = false;

        if ($keyword !== '') {
            $searched = true;
            $pendaftar = Pendaftar::with(['hasilSeleksi', 'periode'])
                ->where('no_pendaftaran', $keyword)
                ->orWhere('nisn', $keyword)
                ->first();
        }

        return view('ppdb.pengumuman', compact('pendaftar', 'keyword', 'searched'));
    }
}
