<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\Siswa;
use App\Models\SyncLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected PeriodePpdb $periode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'nama' => 'Administrator Utama',
            'email' => 'admin@sman1tb.sch.id',
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        $this->periode = PeriodePpdb::create([
            'tahun_ajaran' => '2026/2027',
            'nama_gelombang' => 'Gelombang 1 Reguler',
            'tanggal_buka' => '2026-07-01',
            'tanggal_tutup' => '2026-07-15',
            'kuota' => 360,
            'is_aktif' => true,
        ]);
    }

    public function test_admin_can_access_dashboard_with_synchronized_database_data(): void
    {
        // 1. Buat calon siswa & pendaftar
        $user1 = User::factory()->create(['nama' => 'Calon Siswa 1', 'role' => 'calon_siswa']);
        $pendaftar1 = Pendaftar::create([
            'user_id' => $user1->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'SPMB-2026-0001',
            'nisn' => '0081111111',
            'nama_lengkap' => 'Calon Siswa 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lampung',
            'tanggal_lahir' => '2010-01-01',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Terbanggi Besar',
            'no_hp' => '08123456789',
            'status_pendaftaran' => 'terverifikasi',
        ]);

        $user2 = User::factory()->create(['nama' => 'Calon Siswa Lulus', 'role' => 'siswa']);
        $pendaftar2 = Pendaftar::create([
            'user_id' => $user2->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'SPMB-2026-0002',
            'nisn' => '0082222222',
            'nama_lengkap' => 'Calon Siswa Lulus',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Lampung',
            'tanggal_lahir' => '2010-02-02',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 2 TB',
            'alamat' => 'Terbanggi Besar',
            'no_hp' => '08123456788',
            'status_pendaftaran' => 'lulus',
        ]);

        // 2. Buat Siswa SIAKAD & Kelas
        $kelas = Kelas::create([
            'nama_kelas' => 'X-1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
            'kapasitas' => 36,
        ]);

        $siswa = Siswa::create([
            'user_id' => $user2->id,
            'kelas_id' => $kelas->id,
            'nis' => '262710001',
            'nisn' => '0082222222',
            'nama' => 'Calon Siswa Lulus',
            'jenis_kelamin' => 'P',
            'alamat' => 'Terbanggi Besar',
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        // 3. Buat SyncLog
        SyncLog::create([
            'pendaftar_id' => $pendaftar2->id,
            'siswa_id' => $siswa->id,
            'user_id' => $this->admin->id,
            'waktu_sinkron' => now(),
            'status' => 'BERHASIL',
            'detail_payload' => ['nama' => 'Calon Siswa Lulus'],
        ]);

        // 4. Buat Guru
        Guru::create([
            'user_id' => User::factory()->create(['role' => 'guru'])->id,
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Guru Matematika',
            'jenis_kelamin' => 'L',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Sistem SPMB & SIAKAD');

        // Pastikan data dinamis pendaftar tampil
        $response->assertSee('SPMB-2026-0001');
        $response->assertSee('Calon Siswa 1');
        $response->assertSee('SMPN 1 TB');

        // Pastikan log sinkronisasi tampil
        $response->assertSee('Calon Siswa Lulus');
        $response->assertSee('Berhasil (ID #' . $siswa->id . ')');

        // Pastikan metric cards tampil
        $response->assertSee('Total Pendaftar SPMB');
        $response->assertSee('Berkas Terverifikasi');
        $response->assertSee('Dinyatakan LULUS');
        $response->assertSee('Tersinkron ke SIAKAD');

        // Pastikan status badge tampil sesuai
        $response->assertSee('Diterima');
        $response->assertSee('Terverifikasi');
    }

    public function test_operator_can_access_admin_dashboard(): void
    {
        $operator = User::factory()->create([
            'nama' => 'Operator Sekolah',
            'email' => 'operator@sman1tb.sch.id',
            'role' => 'operator',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($operator)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_siswa_cannot_access_admin_dashboard(): void
    {
        $siswa = User::factory()->create([
            'role' => 'siswa',
        ]);

        $response = $this->actingAs($siswa)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }
}
