<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeparatePortalsTest extends TestCase
{
    use RefreshDatabase;

    protected PeriodePpdb $periode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->periode = PeriodePpdb::create([
            'tahun_ajaran' => '2026/2027',
            'nama_gelombang' => 'Gelombang 1 Reguler',
            'tanggal_buka' => '2026-07-01',
            'tanggal_tutup' => '2026-07-15',
            'kuota' => 540,
            'is_aktif' => true,
        ]);
    }

    public function test_spmb_login_page_renders_successfully(): void
    {
        $response = $this->get('/spmb/login');

        $response->assertStatus(200);
        $response->assertSee('Portal Pendaftaran Ulang (SPMB)');
        $response->assertSee('Khusus Calon Murid Baru');
        $response->assertSee('Login SIAKAD');
    }

    public function test_siakad_login_page_renders_successfully(): void
    {
        $response = $this->get('/siakad/login');

        $response->assertStatus(200);
        $response->assertSee('Portal Akademik (SIAKAD)');
        $response->assertSee('Siswa Aktif');
        $response->assertSee('Login SPMB');
    }

    public function test_calon_siswa_can_login_via_spmb_portal(): void
    {
        $user = User::factory()->create([
            'nama' => 'Ahmad Pendaftar',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('password123'),
            'role' => 'calon_siswa',
            'status_aktif' => true,
        ]);

        Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'SPMB-2026-0001',
            'nisn' => '1234567890',
            'nama_lengkap' => 'Ahmad Pendaftar',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lampung Tengah',
            'tanggal_lahir' => '2010-01-01',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Terbanggi Besar',
            'no_hp' => '081234567890',
            'status_pendaftaran' => 'draft',
        ]);

        $response = $this->post('/spmb/login', [
            'identity' => '1234567890',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('pendaftar.dashboard'));
    }

    public function test_active_siswa_can_login_via_siakad_portal(): void
    {
        $user = User::factory()->create([
            'nama' => 'Budi Siswa Aktif',
            'email' => 'budi.siswa@example.com',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status_aktif' => true,
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'X-1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
            'kapasitas' => 36,
        ]);

        Siswa::create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'nis' => '262710001',
            'nisn' => '1234567891',
            'nama' => 'Budi Siswa Aktif',
            'jenis_kelamin' => 'L',
            'alamat' => 'Lampung Tengah',
            'status_aktif' => true,
            'tahun_masuk' => '2026',
        ]);

        $response = $this->post('/siakad/login', [
            'identity' => '262710001',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('siakad.siswa.dashboard'));
    }

    public function test_calon_siswa_logging_in_to_siakad_is_redirected_to_spmb(): void
    {
        $user = User::factory()->create([
            'nama' => 'Calon Siswa Baru',
            'email' => 'calon@example.com',
            'password' => bcrypt('password123'),
            'role' => 'calon_siswa',
            'status_aktif' => true,
        ]);

        Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'SPMB-2026-0002',
            'nisn' => '1234567892',
            'nama_lengkap' => 'Calon Siswa Baru',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandar Lampung',
            'tanggal_lahir' => '2010-03-03',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 2 TB',
            'alamat' => 'Bandar Jaya',
            'no_hp' => '081234567891',
            'status_pendaftaran' => 'diajukan',
        ]);

        $response = $this->post('/siakad/login', [
            'identity' => 'calon@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('pendaftar.dashboard'));
        $response->assertSessionHas('info');
    }

    public function test_guru_can_login_via_siakad_portal(): void
    {
        $user = User::factory()->create([
            'nama' => 'Drs. Hendro Wibowo',
            'email' => 'hendro@example.com',
            'password' => bcrypt('password123'),
            'role' => 'guru',
            'status_aktif' => true,
        ]);

        Guru::create([
            'user_id' => $user->id,
            'nip' => '198001012005011001',
            'nama_lengkap' => 'Drs. Hendro Wibowo',
            'jenis_kelamin' => 'L',
            'pendidikan_terakhir' => 'S1 Pendidikan',
            'no_hp' => '08111222333',
        ]);

        $response = $this->post('/siakad/login', [
            'identity' => '198001012005011001',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('guru.dashboard'));
    }

    public function test_admin_can_login_via_spmb_or_siakad(): void
    {
        $admin = User::factory()->create([
            'nama' => 'Admin Sekolah',
            'email' => 'admin@sman1tb.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        $response1 = $this->post('/spmb/login', [
            'identity' => 'admin@sman1tb.sch.id',
            'password' => 'password123',
        ]);
        $response1->assertRedirect(route('admin.dashboard'));

        $this->post('/auth/logout');

        $response2 = $this->post('/siakad/login', [
            'identity' => 'admin@sman1tb.sch.id',
            'password' => 'password123',
        ]);
        $response2->assertRedirect(route('admin.dashboard'));
    }
}
