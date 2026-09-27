<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\Siswa;
use App\Models\SyncLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FullIntegrationPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_end_to_end_system_pipeline(): void
    {
        Storage::fake('public');

        // =========================================================================
        // TAHAP 1: PERSIAPAN MASTER OLEH ADMIN
        // =========================================================================
        $admin = User::factory()->create([
            'nama' => 'Administrator Sekolah',
            'email' => 'admin@sman1tb.sch.id',
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        // 1.1 Buat Periode PPDB Aktif
        $periode = PeriodePpdb::create([
            'tahun_ajaran' => '2026/2027',
            'tanggal_buka' => now()->subDays(5)->toDateString(),
            'tanggal_tutup' => now()->addDays(20)->toDateString(),
            'kuota' => 150,
            'is_aktif' => true,
        ]);

        // 1.2 Buat Data Guru di SIAKAD
        $userGuruA = User::factory()->create(['nama' => 'Bambang Sudarmono, M.Pd.', 'role' => 'guru', 'status_aktif' => true]);
        $guruA = Guru::create([
            'user_id' => $userGuruA->id,
            'nip' => '197903152003121002',
            'nama_lengkap' => 'Bambang Sudarmono',
            'gelar' => 'M.Pd.',
            'no_hp' => '081234567800',
        ]);

        $userGuruB = User::factory()->create(['nama' => 'Ratna Dewi, S.Pd.', 'role' => 'guru', 'status_aktif' => true]);
        $guruB = Guru::create([
            'user_id' => $userGuruB->id,
            'nip' => '198406122008012004',
            'nama_lengkap' => 'Ratna Dewi',
            'gelar' => 'S.Pd.',
            'no_hp' => '081298765400',
        ]);

        // =========================================================================
        // TAHAP 2: REGISTRASI & PENDAFTARAN PPDB OLEH CALON SISWA
        // =========================================================================
        // 2.1 Calon Siswa Registrasi Akun SSO Terpusat
        $regResponse = $this->post('/auth/register', [
            'nama' => 'Aditya Pratama',
            'nisn' => '0089123456',
            'email' => 'aditya.pratama@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);
        $regResponse->assertRedirect(route('pendaftar.formulir'));

        $userAditya = User::where('email', 'aditya.pratama@gmail.com')->first();
        $this->assertNotNull($userAditya);
        $this->assertEquals('calon_siswa', $userAditya->role);

        // 2.2 Calon Siswa Mengisi Formulir Pendaftaran
        $pendaftaranResponse = $this->actingAs($userAditya)->post(route('pendaftar.formulir.simpan'), [
            'nisn' => '0089123456',
            'nik' => '1802010101080001',
            'nama_lengkap' => 'Aditya Pratama',
            'tempat_lahir' => 'Terbanggi Besar',
            'tanggal_lahir' => '2008-05-14',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMP Negeri 1 Terbanggi Besar',
            'alamat' => 'Jl. Lintas Sumatera No. 45, Yukum Jaya',
            'no_hp' => '085211223344',
            // Data Orang Tua
            'nama_ayah' => 'Hendra Pratama',
            'pekerjaan_ayah' => 'Wiraswasta',
            'nama_ibu' => 'Siti Aminah',
            'pekerjaan_ibu' => 'Guru',
            'no_hp_ortu' => '081399887766',
        ]);
        $pendaftaranResponse->assertRedirect(route('pendaftar.berkas'));

        $pendaftar = Pendaftar::where('user_id', $userAditya->id)->first();
        $this->assertNotNull($pendaftar);
        $this->assertEquals('0089123456', $pendaftar->nisn);

        // 2.3 Calon Siswa Unggah Seluruh 4 Berkas Persyaratan Utama
        $fileKk = UploadedFile::fake()->create('kartu_keluarga.pdf', 500, 'application/pdf');
        $this->actingAs($userAditya)->post(route('pendaftar.berkas.upload'), [
            'jenis_berkas' => 'kartu_keluarga',
            'file_berkas' => $fileKk,
        ])->assertSessionHas('success');

        $fileAkta = UploadedFile::fake()->create('akta_kelahiran.pdf', 400, 'application/pdf');
        $this->actingAs($userAditya)->post(route('pendaftar.berkas.upload'), [
            'jenis_berkas' => 'akta_kelahiran',
            'file_berkas' => $fileAkta,
        ])->assertSessionHas('success');

        $fileIjazah = UploadedFile::fake()->create('ijazah_skl.pdf', 600, 'application/pdf');
        $this->actingAs($userAditya)->post(route('pendaftar.berkas.upload'), [
            'jenis_berkas' => 'ijazah_skl',
            'file_berkas' => $fileIjazah,
        ])->assertSessionHas('success');

        $fileRapor = UploadedFile::fake()->create('rapor_smp.pdf', 800, 'application/pdf');
        $this->actingAs($userAditya)->post(route('pendaftar.berkas.upload'), [
            'jenis_berkas' => 'rapor',
            'file_berkas' => $fileRapor,
        ])->assertSessionHas('success');

        // Calon siswa mengajukan verifikasi berkas lengkap
        $this->actingAs($userAditya)->post(route('pendaftar.berkas.kirim'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('berkas_pendaftaran', [
            'pendaftar_id' => $pendaftar->id,
            'jenis_berkas' => 'kartu_keluarga',
            'status_verifikasi' => 'menunggu',
        ]);

        // =========================================================================
        // TAHAP 3: VERIFIKASI & SELEKSI KELULUSAN OLEH ADMIN
        // =========================================================================
        // 3.1 Admin Memverifikasi Berkas
        $berkasList = $pendaftar->berkas;
        foreach ($berkasList as $berkas) {
            $this->actingAs($admin)->post("/admin/ppdb/pendaftar/{$pendaftar->id}/verifikasi/{$berkas->id}", [
                'status_verifikasi' => 'valid',
                'catatan' => 'Berkas asli dan jelas terbaca.',
            ])->assertSessionHas('success');
        }

        // 3.2 Admin Melakukan Finalisasi Verifikasi Berkas
        $this->actingAs($admin)->post("/admin/ppdb/pendaftar/{$pendaftar->id}/finalisasi", [
            'status' => 'terverifikasi',
        ])->assertSessionHas('success');

        $pendaftar->refresh();
        $this->assertEquals('terverifikasi', $pendaftar->status_pendaftaran);

        // 3.3 Admin Menetapkan Kelulusan Siswa -> Memicu Pipeline Sinkronisasi Otomatis
        $this->actingAs($admin)->post("/admin/ppdb/seleksi/{$pendaftar->id}", [
            'status' => 'LULUS',
            'catatan' => 'Selamat, Anda dinyatakan Lulus Seleksi PPDB SMAN 1 Terbanggi Besar.',
        ])->assertSessionHas('success');

        // =========================================================================
        // TAHAP 4: VALIDASI SINKRONISASI OTOMATIS (PPDB -> SIAKAD)
        // =========================================================================
        $pendaftar->refresh();
        $userAditya->refresh();

        // 4.1 Status seleksi siswa lulus
        $this->assertNotNull($pendaftar->hasilSeleksi);
        $this->assertEquals('LULUS', $pendaftar->hasilSeleksi->status);

        // 4.2 Role akun SSO di-upgrade dari calon_siswa menjadi siswa
        $this->assertEquals('siswa', $userAditya->role);

        // 4.3 Record Siswa di modul SIAKAD otomatis tercipta
        $siswa = Siswa::where('nisn', $pendaftar->nisn)->first();
        $this->assertNotNull($siswa, 'Record Siswa di SIAKAD harus otomatis terbuat!');
        $this->assertEquals($userAditya->id, $siswa->user_id);
        $this->assertEquals('Aditya Pratama', $siswa->nama);
        $this->assertNotNull($siswa->nis);

        // 4.4 Audit trail tercatat di sync_log
        $this->assertDatabaseHas('sync_log', [
            'pendaftar_id' => $pendaftar->id,
            'siswa_id' => $siswa->id,
            'status' => 'BERHASIL',
        ]);

        // =========================================================================
        // TAHAP 5: MANAJEMEN AKADEMIK SIAKAD OLEH ADMIN
        // =========================================================================
        // 5.1 Admin Membuat Rombel Kelas X-1
        $this->actingAs($admin)->post('/admin/siakad/kelas', [
            'nama_kelas' => 'X MIPA 1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
            'kapasitas' => 36,
            'wali_kelas_id' => $guruA->id,
        ])->assertSessionHas('success');
        $kelasX1 = Kelas::where('nama_kelas', 'X MIPA 1')->first();

        // 5.2 Admin Memploting Aditya ke Kelas X MIPA 1
        $this->actingAs($admin)->post("/admin/siakad/siswa/{$siswa->id}/ploting", [
            'kelas_id' => $kelasX1->id,
        ])->assertSessionHas('success');

        $siswa->refresh();
        $this->assertEquals($kelasX1->id, $siswa->kelas_id);

        // 5.3 Admin Menambahkan Mata Pelajaran
        $this->actingAs($admin)->post('/admin/siakad/mapel', [
            'kode_mapel' => 'MAT-WAJIB-X',
            'nama_mapel' => 'Matematika Wajib X',
            'kkm' => 75,
            'kelompok' => 'umum',
        ])->assertSessionHas('success');
        $mapelMat = MataPelajaran::where('kode_mapel', 'MAT-WAJIB-X')->first();

        // 5.4 Admin Menugaskan Guru A Mengampu Matematika di X MIPA 1
        $this->actingAs($admin)->post('/admin/siakad/pengampu', [
            'guru_id' => $guruA->id,
            'kelas_id' => $kelasX1->id,
            'mapel_id' => $mapelMat->id,
            'tahun_ajaran' => '2026/2027',
        ])->assertSessionHas('success');

        $pengampu = \App\Models\Pengampu::where([
            'guru_id' => $guruA->id,
            'kelas_id' => $kelasX1->id,
            'mapel_id' => $mapelMat->id,
        ])->first();
        $this->assertNotNull($pengampu);

        // =========================================================================
        // TAHAP 6: PORTAL GURU - INPUT NILAI & KALKULASI OTOMATIS
        // =========================================================================
        // 6.1 Guru A login SSO dan melihat tugas mengajarnya
        $responseGuruDash = $this->actingAs($userGuruA)->get('/guru/dashboard');
        $responseGuruDash->assertStatus(200)
            ->assertSee('X MIPA 1')
            ->assertSee('Matematika Wajib X');

        // 6.2 Guru A membuka lembar penilaian dan menginput nilai Aditya
        // Nilai Tugas: 85, UTS: 80, UAS: 90
        // Formula: (85 * 0.3) + (80 * 0.3) + (90 * 0.4) = 25.5 + 24.0 + 36.0 = 85.5
        $responseInputNilai = $this->actingAs($userGuruA)->post("/guru/nilai/{$pengampu->id}", [
            'nilai' => [
                $siswa->id => [
                    'tugas' => 85,
                    'uts' => 80,
                    'uas' => 90,
                    'capaian_kompetensi' => 'Sangat menguasai konsep kalkulus dasar dan fungsi kuadrat.',
                ],
            ],
        ]);
        $responseInputNilai->assertSessionHas('success');

        // 6.3 Verifikasi nilai di database
        $this->assertDatabaseHas('nilai', [
            'siswa_id' => $siswa->id,
            'pengampu_id' => $pengampu->id,
            'nilai_tugas' => 85,
            'nilai_uts' => 80,
            'nilai_uas' => 90,
            'nilai_akhir' => 85.5,
        ]);

        // 6.4 Validasi Keamanan Otorisasi: Guru B (bukan pengampu) mencoba mengedit nilai Aditya -> Ditolak 403 Forbidden
        $responseHack = $this->actingAs($userGuruB)->post("/guru/nilai/{$pengampu->id}", [
            'nilai' => [
                $siswa->id => [
                    'tugas' => 10,
                    'uts' => 10,
                    'uas' => 10,
                ],
            ],
        ]);
        $responseHack->assertStatus(403);

        // =========================================================================
        // TAHAP 7: PORTAL SISWA - CEK KELAS & TRANSKRIP NILAI VIA SSO
        // =========================================================================
        // 7.1 Aditya login via SSO dan melihat halaman Kelas Saya di SIAKAD
        $responseKelasSaya = $this->actingAs($userAditya)->get('/siakad/siswa/kelas');
        $responseKelasSaya->assertStatus(200)
            ->assertSee('X MIPA 1')
            ->assertSee('Bambang Sudarmono')
            ->assertSee('Aditya Pratama');

        // 7.2 Aditya melihat Transkrip Nilai Akademik
        $responseNilaiSaya = $this->actingAs($userAditya)->get('/siakad/siswa/nilai');
        $responseNilaiSaya->assertStatus(200)
            ->assertSee('Matematika Wajib X')
            ->assertSee('85.5')
            ->assertSee('Tuntas');
    }
}
