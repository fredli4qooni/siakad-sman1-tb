<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardPendaftarController extends Controller
{
    /**
     * Tampilan utama Dasbor Calon Siswa.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar()
            ->with(['periode', 'orangTua', 'berkas', 'hasilSeleksi'])
            ->first();

        if (!$pendaftar) {
            return redirect()->route('pendaftar.formulir');
        }

        // Hitung kelengkapan formulir
        $formulirLengkap = !empty($pendaftar->nik) &&
            $pendaftar->asal_sekolah !== '-' &&
            $pendaftar->orangTua !== null &&
            !empty($pendaftar->orangTua->nama_ayah);

        // Hitung kelengkapan berkas
        $berkasCount = $pendaftar->berkas()->whereIn('jenis_berkas', [
            'kartu_keluarga',
            'akta_kelahiran',
            'ijazah_skl',
            'rapor',
        ])->count();

        $hasilSeleksi = $pendaftar->hasilSeleksi;

        return view('ppdb.pendaftar.dashboard', compact(
            'pendaftar',
            'formulirLengkap',
            'berkasCount',
            'hasilSeleksi'
        ));
    }

    /**
     * Halaman khusus status kelulusan siswa.
     */
    public function kelulusan(): View|RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar()
            ->with(['periode', 'hasilSeleksi'])
            ->first();

        if (!$pendaftar) {
            return redirect()->route('pendaftar.formulir');
        }

        $hasilSeleksi = $pendaftar->hasilSeleksi;

        return view('ppdb.pendaftar.kelulusan', compact('pendaftar', 'hasilSeleksi'));
    }

    /**
     * Halaman cetak Bukti Pendaftaran Resmi (Printable HTML).
     */
    public function cetakBukti(): View|RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar()
            ->with(['periode', 'orangTua', 'berkas'])
            ->first();

        if (!$pendaftar) {
            return redirect()->route('pendaftar.formulir');
        }

        return view('ppdb.pendaftar.cetak_bukti', compact('pendaftar'));
    }
}
