<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Pendaftar;
use App\Models\Pengampu;
use App\Models\Siswa;
use App\Models\SyncLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class SiakadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Guru Profil
        $userGuru1 = User::where('email', 'ahmad.fauzi@sman1tb.sch.id')->first();
        $userGuru2 = User::where('email', 'siti.aminah@sman1tb.sch.id')->first();
        $userGuru3 = User::where('email', 'hendra.wijaya@sman1tb.sch.id')->first();

        $guru1 = Guru::updateOrCreate(
            ['nip' => '197508122000031001'],
            [
                'user_id' => $userGuru1?->id,
                'nama_lengkap' => 'Drs. Ahmad Fauzi',
                'gelar' => 'M.Pd.',
                'no_hp' => '081272345678',
                'alamat' => 'Terbanggi Besar, Lampung Tengah',
                'status_aktif' => true,
            ]
        );

        $guru2 = Guru::updateOrCreate(
            ['nip' => '198204152006042012'],
            [
                'user_id' => $userGuru2?->id,
                'nama_lengkap' => 'Siti Aminah',
                'gelar' => 'S.Pd.',
                'no_hp' => '081369871234',
                'alamat' => 'Bandar Jaya, Lampung Tengah',
                'status_aktif' => true,
            ]
        );

        $guru3 = Guru::updateOrCreate(
            ['nip' => '198901202015031002'],
            [
                'user_id' => $userGuru3?->id,
                'nama_lengkap' => 'Hendra Wijaya',
                'gelar' => 'S.Si.',
                'no_hp' => '082181234987',
                'alamat' => 'Gunung Sugih, Lampung Tengah',
                'status_aktif' => true,
            ]
        );

        // 2. Data Kelas / Rombel
        $kelas1 = Kelas::updateOrCreate(
            ['nama_kelas' => 'Kelas X MIPA 1', 'tahun_ajaran' => '2026/2027'],
            [
                'tingkat' => 'X',
                'wali_kelas_id' => $guru1->id,
                'kapasitas' => 36,
            ]
        );

        $kelas2 = Kelas::updateOrCreate(
            ['nama_kelas' => 'Kelas X MIPA 2', 'tahun_ajaran' => '2026/2027'],
            [
                'tingkat' => 'X',
                'wali_kelas_id' => $guru2->id,
                'kapasitas' => 36,
            ]
        );

        $kelas3 = Kelas::updateOrCreate(
            ['nama_kelas' => 'Kelas X IPS 1', 'tahun_ajaran' => '2026/2027'],
            [
                'tingkat' => 'X',
                'wali_kelas_id' => $guru3->id,
                'kapasitas' => 36,
            ]
        );

        // 3. Data Master Mata Pelajaran
        $mapelMtk = MataPelajaran::updateOrCreate(
            ['kode_mapel' => 'MTK-WAJIB'],
            ['nama_mapel' => 'Matematika Wajib', 'kkm' => 75, 'kelompok' => 'umum']
        );

        $mapelBin = MataPelajaran::updateOrCreate(
            ['kode_mapel' => 'BIN-WAJIB'],
            ['nama_mapel' => 'Bahasa Indonesia', 'kkm' => 75, 'kelompok' => 'umum']
        );

        $mapelBig = MataPelajaran::updateOrCreate(
            ['kode_mapel' => 'BIG-WAJIB'],
            ['nama_mapel' => 'Bahasa Inggris', 'kkm' => 75, 'kelompok' => 'umum']
        );

        $mapelFis = MataPelajaran::updateOrCreate(
            ['kode_mapel' => 'FIS-PEM'],
            ['nama_mapel' => 'Fisika', 'kkm' => 75, 'kelompok' => 'peminatan']
        );

        // 4. Data Siswa SIAKAD (Budi Santoso - Ditautkan ke Akun SSO & Siswa Lulus)
        $userSiswa = User::where('email', 'budi.santoso@siswa.sman1tb.sch.id')->first();
        $pendaftarBudi = Pendaftar::where('nisn', '0071234567')->first();

        if ($userSiswa) {
            $siswa = Siswa::updateOrCreate(
                ['nisn' => '0071234567'],
                [
                    'user_id' => $userSiswa->id,
                    'nis' => '26001',
                    'nama' => 'Budi Santoso',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Lintas Sumatera No. 45, Terbanggi Besar, Lampung Tengah',
                    'kelas_id' => $kelas1->id,
                    'tahun_masuk' => '2026',
                    'status_aktif' => true,
                ]
            );

            // 5. Pencatatan Sync Log Pembuktian Integrasi PPDB -> SIAKAD
            if ($pendaftarBudi) {
                SyncLog::updateOrCreate(
                    ['pendaftar_id' => $pendaftarBudi->id],
                    [
                        'siswa_id' => $siswa->id,
                        'user_id' => $userSiswa->id,
                        'waktu_sinkron' => now(),
                        'status' => 'BERHASIL',
                        'detail_payload' => [
                            'nisn' => $pendaftarBudi->nisn,
                            'nama' => $pendaftarBudi->nama_lengkap,
                            'asal_sekolah' => $pendaftarBudi->asal_sekolah,
                            'disinkron_otomatis_oleh' => 'SyncService::class',
                        ],
                        'error_message' => null,
                    ]
                );
            }

            // 6. Penugasan Mengajar (Pengampu)
            $pengampuMtk = Pengampu::updateOrCreate(
                [
                    'guru_id' => $guru1->id,
                    'kelas_id' => $kelas1->id,
                    'mapel_id' => $mapelMtk->id,
                    'tahun_ajaran' => '2026/2027',
                    'semester' => 'ganjil',
                ]
            );

            $pengampuBin = Pengampu::updateOrCreate(
                [
                    'guru_id' => $guru2->id,
                    'kelas_id' => $kelas1->id,
                    'mapel_id' => $mapelBin->id,
                    'tahun_ajaran' => '2026/2027',
                    'semester' => 'ganjil',
                ]
            );

            $pengampuFis = Pengampu::updateOrCreate(
                [
                    'guru_id' => $guru3->id,
                    'kelas_id' => $kelas1->id,
                    'mapel_id' => $mapelFis->id,
                    'tahun_ajaran' => '2026/2027',
                    'semester' => 'ganjil',
                ]
            );

            // 7. Input Nilai Siswa Contoh
            Nilai::updateOrCreate(
                ['siswa_id' => $siswa->id, 'pengampu_id' => $pengampuMtk->id],
                [
                    'nilai_tugas' => 85.00,
                    'nilai_uts' => 82.00,
                    'nilai_uas' => 88.00,
                    'nilai_akhir' => 85.30,
                    'capaian_kompetensi' => 'Menunjukkan pemahaman yang sangat baik dalam materi aljabar dan fungsi kuadrat.',
                ]
            );

            Nilai::updateOrCreate(
                ['siswa_id' => $siswa->id, 'pengampu_id' => $pengampuBin->id],
                [
                    'nilai_tugas' => 88.00,
                    'nilai_uts' => 85.00,
                    'nilai_uas' => 90.00,
                    'nilai_akhir' => 87.90,
                    'capaian_kompetensi' => 'Sangat cakap dalam menganalisis dan menyusun teks laporan hasil observasi.',
                ]
            );
        }
    }
}
