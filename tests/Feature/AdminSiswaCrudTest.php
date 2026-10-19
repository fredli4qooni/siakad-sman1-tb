<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSiswaCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Kelas $kelas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'nama' => 'Administrator SIAKAD',
            'email' => 'admin.siakad@sman1tb.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        $this->kelas = Kelas::create([
            'nama_kelas' => 'X-1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
            'kapasitas' => 36,
        ]);
    }

    public function test_admin_can_view_siswa_index_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.siakad.siswa.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Data Siswa SIAKAD');
        $response->assertSee('Tambah Siswa Baru');
    }

    public function test_admin_can_create_new_siswa_with_sso_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.siakad.siswa.simpan'), [
            'nama' => 'Aditya Pratama',
            'nisn' => '0089988776',
            'nis' => '262710055',
            'email' => 'aditya@siswa.sman1tb.sch.id',
            'jenis_kelamin' => 'L',
            'kelas_id' => $this->kelas->id,
            'tahun_masuk' => '2026',
            'password' => 'secret123',
            'alamat' => 'Terbanggi Besar, Lampung Tengah',
            'status_aktif' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'nama' => 'Aditya Pratama',
            'nisn' => '0089988776',
            'nis' => '262710055',
            'jenis_kelamin' => 'L',
            'kelas_id' => $this->kelas->id,
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'nama' => 'Aditya Pratama',
            'email' => 'aditya@siswa.sman1tb.sch.id',
            'role' => 'siswa',
            'status_aktif' => true,
        ]);

        $user = User::where('email', 'aditya@siswa.sman1tb.sch.id')->first();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_admin_can_update_existing_siswa_and_user_account(): void
    {
        $user = User::factory()->create([
            'nama' => 'Dewi Lestari',
            'email' => 'dewi@siswa.sman1tb.sch.id',
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'kelas_id' => null,
            'nisn' => '0081122334',
            'nis' => '262710099',
            'nama' => 'Dewi Lestari',
            'jenis_kelamin' => 'P',
            'alamat' => 'Bandar Jaya Barat',
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.siakad.siswa.update', $siswa->id), [
            'nama' => 'Dewi Lestari S.Pd (Updated)',
            'nisn' => '0081122334', // unchanged
            'nis' => '262710099',
            'email' => 'dewi.updated@siswa.sman1tb.sch.id',
            'jenis_kelamin' => 'P',
            'kelas_id' => $this->kelas->id,
            'tahun_masuk' => '2026',
            'alamat' => 'Alamat baru di Terbanggi',
            'status_aktif' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'nama' => 'Dewi Lestari S.Pd (Updated)',
            'kelas_id' => $this->kelas->id,
            'alamat' => 'Alamat baru di Terbanggi',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama' => 'Dewi Lestari S.Pd (Updated)',
            'email' => 'dewi.updated@siswa.sman1tb.sch.id',
        ]);
    }

    public function test_admin_can_reset_siswa_password(): void
    {
        $user = User::factory()->create([
            'nama' => 'Rian Hidayat',
            'email' => 'rian@siswa.sman1tb.sch.id',
            'password' => bcrypt('oldpassword'),
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nisn' => '0085566778',
            'nama' => 'Rian Hidayat',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.siakad.siswa.reset_password', $siswa->id), [
            'password' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_admin_can_delete_siswa(): void
    {
        $user = User::factory()->create([
            'nama' => 'Siswa Dihapus',
            'email' => 'hapus@siswa.sman1tb.sch.id',
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nisn' => '0087788990',
            'nama' => 'Siswa Dihapus',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.siakad.siswa.hapus', $siswa->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('siswa', ['id' => $siswa->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_validation_prevents_duplicate_nisn_and_email(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'kembar@siswa.sch.id',
        ]);

        Siswa::create([
            'user_id' => $existingUser->id,
            'nisn' => '0081234567',
            'nama' => 'Siswa Pertama',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.siakad.siswa.simpan'), [
            'nama' => 'Siswa Kedua',
            'nisn' => '0081234567', // Duplicate
            'email' => 'kembar@siswa.sch.id', // Duplicate
            'jenis_kelamin' => 'P',
            'tahun_masuk' => '2026',
        ]);

        $response->assertSessionHasErrors(['nisn', 'email']);
    }
}
