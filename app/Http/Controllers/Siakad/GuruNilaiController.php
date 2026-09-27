<?php

namespace App\Http\Controllers\Siakad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Siakad\SimpanNilaiRequest;
use App\Models\Nilai;
use App\Models\Pengampu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GuruNilaiController extends Controller
{
    /**
     * Halaman Dasbor Guru Akademik.
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $guru = $user->guru;

        $pengampuList = $guru ? Pengampu::with(['kelas.siswa', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->get() : collect();

        return view('guru.dashboard', compact('guru', 'pengampuList'));
    }

    /**
     * Daftar Penugasan Mengajar Guru.
     */
    public function pengampuIndex(): View
    {
        $user = Auth::user();
        $guru = $user->guru;

        $pengampuList = $guru ? Pengampu::with(['kelas.siswa', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->get() : collect();

        return view('guru.pengampu', compact('guru', 'pengampuList'));
    }

    /**
     * Lembar Input Nilai Siswa per Rombel & Mata Pelajaran.
     */
    public function inputNilai(Pengampu $pengampu): View
    {
        // Validasi otorisasi: Guru hanya boleh menginput nilai kelas & mapel yang diampunya
        Gate::authorize('input', $pengampu);

        $pengampu->load(['kelas.siswa' => fn($q) => $q->orderBy('nama'), 'mataPelajaran', 'guru']);

        $nilaiList = Nilai::where('pengampu_id', $pengampu->id)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai', compact('pengampu', 'nilaiList'));
    }

    /**
     * Memproses penyimpanan nilai siswa dengan kalkulasi otomatis.
     */
    public function simpanNilai(SimpanNilaiRequest $request, Pengampu $pengampu): RedirectResponse
    {
        Gate::authorize('input', $pengampu);

        $validated = $request->validated();
        $dataNilai = $validated['nilai'];

        foreach ($dataNilai as $siswaId => $item) {
            $tugas = isset($item['tugas']) && $item['tugas'] !== '' ? (float) $item['tugas'] : null;
            $uts = isset($item['uts']) && $item['uts'] !== '' ? (float) $item['uts'] : null;
            $uas = isset($item['uas']) && $item['uas'] !== '' ? (float) $item['uas'] : null;

            // Hitung nilai akhir otomatis: 30% Tugas + 30% UTS + 40% UAS jika data tersedia
            $nilaiAkhir = null;
            if ($tugas !== null || $uts !== null || $uas !== null) {
                $nilaiAkhir = round((($tugas ?? 0) * 0.3) + (($uts ?? 0) * 0.3) + (($uas ?? 0) * 0.4), 2);
            }

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'pengampu_id' => $pengampu->id,
                ],
                [
                    'nilai_tugas' => $tugas,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'capaian_kompetensi' => $item['capaian_kompetensi'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Nilai siswa berhasil disimpan dan nilai akhir otomatis dihitung.');
    }
}
