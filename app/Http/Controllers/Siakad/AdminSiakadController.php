<?php

namespace App\Http\Controllers\Siakad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Siakad\GuruRequest;
use App\Http\Requests\Siakad\KelasRequest;
use App\Http\Requests\Siakad\MapelRequest;
use App\Http\Requests\Siakad\PengampuRequest;
use App\Http\Requests\Siakad\SiswaRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengampu;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminSiakadController extends Controller
{
    /**
     * 1. Manajemen Kelas (Rombel)
     */
    public function kelasIndex(): View
    {
        $kelasList = Kelas::with(['waliKelas', 'siswa'])
            ->withCount('siswa')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $daftarGuru = Guru::orderBy('nama_lengkap')->get();

        return view('admin.siakad.kelas', compact('kelasList', 'daftarGuru'));
    }

    public function simpanKelas(KelasRequest $request, ?Kelas $kelas = null): RedirectResponse
    {
        $validated = $request->validated();

        if ($kelas && $kelas->exists) {
            $kelas->update($validated);
            $msg = "Data kelas {$kelas->nama_kelas} berhasil diperbarui.";
        } else {
            $kelas = Kelas::create($validated);
            $msg = "Kelas baru {$kelas->nama_kelas} berhasil ditambahkan.";
        }

        return back()->with('success', $msg);
    }

    /**
     * 2. Manajemen Siswa (CRUD) & Penempatan Kelas (Ploting Rombel)
     */
    public function siswaIndex(Request $request): View
    {
        $query = Siswa::with(['kelas', 'user', 'syncLog']);

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%")
                    ->orWhere('nis', 'like', "%{$keyword}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('email', 'like', "%{$keyword}%"));
            });
        }

        if ($request->filled('kelas_id') && $request->input('kelas_id') !== 'semua') {
            if ($request->input('kelas_id') === 'belum_ada') {
                $query->whereNull('kelas_id');
            } else {
                $query->where('kelas_id', $request->input('kelas_id'));
            }
        }

        if ($request->filled('status_aktif') && $request->input('status_aktif') !== 'semua') {
            $query->where('status_aktif', $request->input('status_aktif') === '1');
        }

        $siswaList = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $totalSiswa = Siswa::count();
        $belumDitempatkan = Siswa::whereNull('kelas_id')->count();
        $totalAktif = Siswa::where('status_aktif', true)->count();
        $totalLaki = Siswa::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Siswa::where('jenis_kelamin', 'P')->count();

        return view('admin.siakad.siswa', compact(
            'siswaList',
            'kelasList',
            'totalSiswa',
            'belumDitempatkan',
            'totalAktif',
            'totalLaki',
            'totalPerempuan'
        ));
    }

    /**
     * Simpan data siswa baru atau perbarui siswa yang sudah ada (Create / Update).
     */
    public function simpanSiswa(SiswaRequest $request, ?Siswa $siswa = null): RedirectResponse
    {
        $validated = $request->validated();
        $statusAktif = $request->has('status_aktif') ? $request->boolean('status_aktif') : true;

        DB::transaction(function () use ($validated, $statusAktif, $siswa) {
            if ($siswa && $siswa->exists) {
                // Update Data Siswa
                $siswa->update([
                    'nisn' => $validated['nisn'],
                    'nis' => $validated['nis'] ?? null,
                    'nama' => $validated['nama'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'alamat' => $validated['alamat'] ?? null,
                    'kelas_id' => $validated['kelas_id'] ?? null,
                    'tahun_masuk' => $validated['tahun_masuk'],
                    'status_aktif' => $statusAktif,
                ]);

                // Update Akun User SSO Siswa
                if ($siswa->user) {
                    $userData = [
                        'nama' => $validated['nama'],
                        'email' => $validated['email'],
                        'status_aktif' => $statusAktif,
                    ];

                    if (!empty($validated['password'])) {
                        $userData['password'] = Hash::make($validated['password']);
                    }

                    $siswa->user->update($userData);
                }
            } else {
                // 1. Buat User Akun SSO Siswa Baru
                $user = User::create([
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'] ?: 'password123'),
                    'role' => 'siswa',
                    'status_aktif' => $statusAktif,
                ]);

                // 2. Buat Record Siswa SIAKAD
                Siswa::create([
                    'user_id' => $user->id,
                    'nisn' => $validated['nisn'],
                    'nis' => $validated['nis'] ?? null,
                    'nama' => $validated['nama'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'alamat' => $validated['alamat'] ?? null,
                    'kelas_id' => $validated['kelas_id'] ?? null,
                    'tahun_masuk' => $validated['tahun_masuk'],
                    'status_aktif' => $statusAktif,
                ]);
            }
        });

        $action = ($siswa && $siswa->exists) ? 'diperbarui' : 'ditambahkan';
        return back()->with('success', "Data siswa {$validated['nama']} berhasil {$action}.");
    }

    /**
     * Hapus data siswa dari sistem SIAKAD.
     */
    public function hapusSiswa(Siswa $siswa): RedirectResponse
    {
        $nama = $siswa->nama;

        DB::transaction(function () use ($siswa) {
            $user = $siswa->user;
            $siswa->delete();

            // Jika user hanya berperan sebagai siswa dan tidak punya pendaftar SPMB
            if ($user && $user->role === 'siswa' && !$user->pendaftar()->exists()) {
                $user->delete();
            }
        });

        return back()->with('success', "Data siswa {$nama} berhasil dihapus dari sistem.");
    }

    /**
     * Reset kata sandi akun SSO siswa.
     */
    public function resetPasswordSiswa(Request $request, Siswa $siswa): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
        ]);

        if ($siswa->user) {
            $siswa->user->update([
                'password' => Hash::make($request->input('password')),
            ]);

            return back()->with('success', "Kata sandi akun SSO siswa {$siswa->nama} berhasil direset.");
        }

        return back()->with('error', 'Akun SSO untuk siswa ini tidak ditemukan.');
    }

    public function updatePlotingSiswa(Request $request, Siswa $siswa): RedirectResponse
    {
        $request->validate([
            'kelas_id' => ['nullable', 'exists:kelas,id'],
        ]);

        $siswa->update([
            'kelas_id' => $request->input('kelas_id'),
        ]);

        $namaKelas = $siswa->kelas ? $siswa->kelas->nama_kelas : 'Belum Ditempatkan';

        return back()->with('success', "Penempatan kelas {$siswa->nama} berhasil diubah ke {$namaKelas}.");
    }

    public function plotingMassal(Request $request, Kelas $kelas): RedirectResponse
    {
        $request->validate([
            'siswa_ids' => ['required', 'array'],
            'siswa_ids.*' => ['exists:siswa,id'],
        ]);

        $siswaIds = $request->input('siswa_ids');

        Siswa::whereIn('id', $siswaIds)->update(['kelas_id' => $kelas->id]);

        return back()->with('success', count($siswaIds) . " siswa berhasil dimasukkan ke {$kelas->nama_kelas}.");
    }

    /**
     * 3. Manajemen Guru & Akun SSO
     */
    public function guruIndex(): View
    {
        $guruList = Guru::with(['user', 'pengampu.mataPelajaran'])
            ->withCount('pengampu')
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.siakad.guru', compact('guruList'));
    }

    public function simpanGuru(GuruRequest $request, ?Guru $guru = null): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $guru) {
            if ($guru && $guru->exists) {
                $guru->update([
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'gelar' => $validated['gelar'] ?? null,
                    'nip' => $validated['nip'],
                    'no_hp' => $validated['no_hp'] ?? null,
                ]);

                if ($guru->user) {
                    $guru->user->update([
                        'nama' => $validated['nama_lengkap'] . ($validated['gelar'] ? ', ' . $validated['gelar'] : ''),
                        'email' => $validated['email'],
                    ]);
                }
            } else {
                // Buat User SSO Guru
                $user = User::create([
                    'nama' => $validated['nama_lengkap'] . ($validated['gelar'] ? ', ' . $validated['gelar'] : ''),
                    'email' => $validated['email'],
                    'password' => Hash::make('password'), // password default
                    'role' => 'guru',
                    'status_aktif' => true,
                ]);

                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $validated['nip'],
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'gelar' => $validated['gelar'] ?? null,
                    'no_hp' => $validated['no_hp'] ?? null,
                ]);
            }
        });

        return back()->with('success', 'Data guru dan akun SSO berhasil disimpan.');
    }

    /**
     * 4. Manajemen Mata Pelajaran
     */
    public function mapelIndex(): View
    {
        $mapelList = MataPelajaran::withCount('pengampu')
            ->orderBy('kelompok')
            ->orderBy('nama_mapel')
            ->get();

        return view('admin.siakad.mapel', compact('mapelList'));
    }

    public function simpanMapel(MapelRequest $request, ?MataPelajaran $mapel = null): RedirectResponse
    {
        $validated = $request->validated();

        if ($mapel && $mapel->exists) {
            $mapel->update($validated);
            $msg = "Mata pelajaran {$mapel->nama_mapel} berhasil diperbarui.";
        } else {
            $mapel = MataPelajaran::create($validated);
            $msg = "Mata pelajaran baru {$mapel->nama_mapel} berhasil ditambahkan.";
        }

        return back()->with('success', $msg);
    }

    /**
     * 5. Manajemen Guru Pengampu (Penugasan Mengajar)
     */
    public function pengampuIndex(): View
    {
        $pengampuList = Pengampu::with(['guru', 'kelas', 'mataPelajaran'])
            ->orderBy('tahun_ajaran', 'desc')
            ->get();

        $daftarGuru = Guru::orderBy('nama_lengkap')->get();
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();
        $daftarMapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('admin.siakad.pengampu', compact(
            'pengampuList',
            'daftarGuru',
            'daftarKelas',
            'daftarMapel'
        ));
    }

    public function simpanPengampu(PengampuRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Cek duplikasi alokasi guru pengampu pada kelas dan mapel yang sama di tahun ajaran yang sama
        $exists = Pengampu::where('guru_id', $validated['guru_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('mapel_id', $validated['mapel_id'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Penugasan mengajar untuk guru, kelas, dan mata pelajaran ini sudah ada.');
        }

        Pengampu::create($validated);

        return back()->with('success', 'Penugasan guru pengampu berhasil ditambahkan.');
    }

    public function hapusPengampu(Pengampu $pengampu): RedirectResponse
    {
        $pengampu->delete();

        return back()->with('success', 'Penugasan mengajar berhasil dihapus.');
    }
}
