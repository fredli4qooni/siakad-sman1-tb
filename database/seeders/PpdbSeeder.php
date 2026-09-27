<?php

namespace Database\Seeders;

use App\Models\BerkasPendaftaran;
use App\Models\HasilSeleksi;
use App\Models\OrangTua;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\User;
use Illuminate\Database\Seeder;

class PpdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Periode PPDB Aktif 2026/2027
        $periode = PeriodePpdb::updateOrCreate(
            ['tahun_ajaran' => '2026/2027'],
            [
                'nama_gelombang' => 'Gelombang 1 Reguler',
                'tanggal_buka' => '2026-07-01',
                'tanggal_tutup' => '2026-07-15',
                'kuota' => 540,
                'is_aktif' => true,
                'deskripsi' => 'Penerimaan Peserta Didik Baru SMAN 1 Terbanggi Besar Tahun Ajaran 2026/2027',
            ]
        );

        // Ambil User Calon Siswa & User Siswa Lulus
        $userCalon = User::where('email', 'rina.marlina@gmail.com')->first();
        $userLulus = User::where('email', 'budi.santoso@siswa.sman1tb.sch.id')->first();
        $admin = User::where('email', 'admin@sman1tb.sch.id')->first();

        // 2. Data Pendaftar 1 (Budi Santoso - Dinyatakan LULUS & Disinkronkan ke SIAKAD)
        if ($userLulus) {
            $pendaftarLulus = Pendaftar::updateOrCreate(
                ['no_pendaftaran' => 'PPDB-2026-0001'],
                [
                    'user_id' => $userLulus->id,
                    'periode_id' => $periode->id,
                    'nisn' => '0071234567',
                    'nik' => '1802010101070001',
                    'nama_lengkap' => 'Budi Santoso',
                    'jenis_kelamin' => 'L',
                    'tempat_lahir' => 'Terbanggi Besar',
                    'tanggal_lahir' => '2009-05-12',
                    'agama' => 'Islam',
                    'asal_sekolah' => 'SMP Negeri 1 Terbanggi Besar',
                    'alamat' => 'Jl. Lintas Sumatera No. 45, Terbanggi Besar, Lampung Tengah',
                    'no_hp' => '081234567890',
                    'status_pendaftaran' => 'terverifikasi',
                    'catatan_verifikasi' => 'Berkas lengkap dan sesuai fisik.',
                ]
            );

            OrangTua::updateOrCreate(
                ['pendaftar_id' => $pendaftarLulus->id],
                [
                    'nama_ayah' => 'Joko Santoso',
                    'pekerjaan_ayah' => 'Wiraswasta',
                    'penghasilan_ayah' => 'Rp 3.000.000 - Rp 5.000.000',
                    'nama_ibu' => 'Sri Wahyuni',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'penghasilan_ibu' => '< Rp 1.000.000',
                    'no_hp_ortu' => '081398765432',
                    'alamat_ortu' => 'Jl. Lintas Sumatera No. 45, Terbanggi Besar, Lampung Tengah',
                ]
            );

            // Berkas Budi
            BerkasPendaftaran::updateOrCreate(
                ['pendaftar_id' => $pendaftarLulus->id, 'jenis_berkas' => 'kartu_keluarga'],
                [
                    'nama_file_asli' => 'kk_budi_santoso.pdf',
                    'file_path' => 'berkas_ppdb/kk_budi_santoso.pdf',
                    'ukuran_file' => 524288,
                    'mime_type' => 'application/pdf',
                    'status_verifikasi' => 'valid',
                    'catatan' => 'Sesuai data Disdukcapil',
                    'diverifikasi_oleh' => $admin?->id,
                    'diverifikasi_pada' => now(),
                ]
            );

            // Hasil Seleksi Budi (LULUS)
            HasilSeleksi::updateOrCreate(
                ['pendaftar_id' => $pendaftarLulus->id],
                [
                    'status' => 'LULUS',
                    'catatan' => 'Dinyatakan diterima melalui jalur seleksi reguler.',
                    'diverifikasi_oleh' => $admin?->id,
                    'tanggal_pengumuman' => now(),
                ]
            );
        }

        // 3. Data Pendaftar 2 (Rina Marlina - Status Pendaftaran Aktif / Menunggu Verifikasi)
        if ($userCalon) {
            $pendaftarCalon = Pendaftar::updateOrCreate(
                ['no_pendaftaran' => 'PPDB-2026-0002'],
                [
                    'user_id' => $userCalon->id,
                    'periode_id' => $periode->id,
                    'nisn' => '0089876543',
                    'nik' => '1802015203080002',
                    'nama_lengkap' => 'Rina Marlina',
                    'jenis_kelamin' => 'P',
                    'tempat_lahir' => 'Bandar Jaya',
                    'tanggal_lahir' => '2010-03-22',
                    'agama' => 'Islam',
                    'asal_sekolah' => 'SMP Negeri 2 Terbanggi Besar',
                    'alamat' => 'Jl. Proklamasi No. 12, Yukum Jaya, Terbanggi Besar',
                    'no_hp' => '085278901234',
                    'status_pendaftaran' => 'terkirim',
                    'catatan_verifikasi' => null,
                ]
            );

            OrangTua::updateOrCreate(
                ['pendaftar_id' => $pendaftarCalon->id],
                [
                    'nama_ayah' => 'Bambang Supriyanto',
                    'pekerjaan_ayah' => 'PNS',
                    'penghasilan_ayah' => '> Rp 5.000.000',
                    'nama_ibu' => 'Ratna Dewi',
                    'pekerjaan_ibu' => 'Guru',
                    'penghasilan_ibu' => 'Rp 3.000.000 - Rp 5.000.000',
                    'no_hp_ortu' => '081265432109',
                    'alamat_ortu' => 'Jl. Proklamasi No. 12, Yukum Jaya, Terbanggi Besar',
                ]
            );

            HasilSeleksi::updateOrCreate(
                ['pendaftar_id' => $pendaftarCalon->id],
                [
                    'status' => 'MENUNGGU',
                    'catatan' => 'Berkas dalam antrean verifikasi operator.',
                ]
            );
        }
    }
}
