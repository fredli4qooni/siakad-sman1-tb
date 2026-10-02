<?php

namespace App\Http\Controllers\Siakad;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiswaPortalController extends Controller
{
    /**
     * Tampilan Dasbor Utama SIAKAD Siswa (Khusus Data Siswa Sendiri).
     */
    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->route('pendaftar.dashboard')
                ->with('error', 'Akun Anda belum terdaftar sebagai siswa aktif di SIAKAD. Harap selesaikan proses daftar ulang & verifikasi fisik.');
        }

        $siswa->load(['kelas.waliKelas', 'user']);
        $pendaftar = $user->pendaftar()->with('orangTua')->first();

        return view('siakad.siswa.dashboard', compact('siswa', 'pendaftar'));
    }

    /**
     * Tampilan Rombel Kelas & Teman Sekelas Siswa.
     */
    public function kelas(): View|RedirectResponse
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->route('pendaftar.dashboard')
                ->with('error', 'Akun Anda belum terdaftar sebagai siswa aktif di SIAKAD.');
        }

        $kelas = $siswa->kelas()->with('waliKelas')->first();

        $temanSekelas = $kelas ? Siswa::where('kelas_id', $kelas->id)
            ->orderBy('nama')
            ->get() : collect();

        return view('siakad.siswa.kelas', compact('siswa', 'kelas', 'temanSekelas'));
    }

    /**
     * Tampilan Rapor & Nilai Akademik Siswa.
     */
    public function nilai(): View|RedirectResponse
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->route('pendaftar.dashboard')
                ->with('error', 'Akun Anda belum terdaftar sebagai siswa aktif di SIAKAD.');
        }

        $daftarNilai = Nilai::with(['pengampu.mataPelajaran', 'pengampu.guru'])
            ->where('siswa_id', $siswa->id)
            ->get();

        $rataRata = $daftarNilai->whereNotNull('nilai_akhir')->avg('nilai_akhir');
        $tertinggi = $daftarNilai->whereNotNull('nilai_akhir')->max('nilai_akhir');

        return view('siakad.siswa.nilai', compact('siswa', 'daftarNilai', 'rataRata', 'tertinggi'));
    }
}
