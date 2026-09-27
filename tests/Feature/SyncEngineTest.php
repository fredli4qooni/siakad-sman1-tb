<?php

namespace Tests\Feature;

use App\Models\HasilSeleksi;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\Siswa;
use App\Models\SyncLog;
use App\Models\User;
use App\Services\SyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncEngineTest extends TestCase
{
    use RefreshDatabase;

    protected PeriodePpdb $periode;
    protected SyncService $syncService;

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

        $this->syncService = app(SyncService::class);
    }

    public function test_sync_service_creates_siswa_and_upgrades_user_role(): void
    {
        $user = User::factory()->create([
            'role' => 'calon_siswa',
            'status_aktif' => true,
        ]);

        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0001',
            'nisn' => '0011223344',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Terbanggi Besar',
            'tanggal_lahir' => '2010-02-14',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Jl. Merdeka No. 10',
            'no_hp' => '081234567890',
            'status_pendaftaran' => 'lulus',
        ]);

        $result = $this->syncService->syncPendaftar($pendaftar);

        $this->assertTrue($result['success']);

        // 1. Siswa record created
        $this->assertDatabaseHas('siswa', [
            'nisn' => '0011223344',
            'nama' => 'Budi Santoso',
            'user_id' => $user->id,
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        // 2. User role upgraded to siswa
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'siswa',
        ]);

        // 3. Audit trail log recorded
        $this->assertDatabaseHas('sync_log', [
            'pendaftar_id' => $pendaftar->id,
            'status' => 'BERHASIL',
        ]);
    }

    public function test_sync_service_is_idempotent(): void
    {
        $user = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0002',
            'nisn' => '0022334455',
            'nama_lengkap' => 'Dewi Sartika',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Bandar Jaya',
            'tanggal_lahir' => '2010-04-18',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 2 TB',
            'alamat' => 'Jl. Kartini No. 5',
            'no_hp' => '081398765432',
            'status_pendaftaran' => 'lulus',
        ]);

        // Sync twice
        $this->syncService->syncPendaftar($pendaftar);
        $this->syncService->syncPendaftar($pendaftar);

        // Should not duplicate Siswa
        $this->assertEquals(1, Siswa::where('nisn', '0022334455')->count());
    }

    public function test_sync_service_rejects_candidate_not_marked_lulus(): void
    {
        $user = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0003',
            'nisn' => '0033445566',
            'nama_lengkap' => 'Calon Belum Lulus',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lampung',
            'tanggal_lahir' => '2010-05-20',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Jl. Pattimura',
            'no_hp' => '081234567899',
            'status_pendaftaran' => 'draft',
        ]);

        $result = $this->syncService->syncPendaftar($pendaftar);

        $this->assertFalse($result['success']);
        $this->assertEquals(0, Siswa::where('nisn', '0033445566')->count());
    }

    public function test_admin_marking_lulus_automatically_triggers_sync_to_siakad(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status_aktif' => true]);
        $calon = User::factory()->create(['role' => 'calon_siswa']);

        $pendaftar = Pendaftar::create([
            'user_id' => $calon->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0004',
            'nisn' => '0044556677',
            'nama_lengkap' => 'Siswa Lolos Verifikasi',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Terbanggi Besar',
            'tanggal_lahir' => '2010-06-12',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Jl. Sudirman',
            'no_hp' => '081299887766',
            'status_pendaftaran' => 'terverifikasi',
        ]);

        $response = $this->actingAs($admin)->post("/admin/ppdb/seleksi/{$pendaftar->id}", [
            'status' => 'LULUS',
            'catatan' => 'Lolos jalur zonasi',
        ]);

        $response->assertSessionHas('success');

        // Otomatis terdaftar sebagai Siswa di SIAKAD
        $this->assertDatabaseHas('siswa', [
            'nisn' => '0044556677',
            'nama' => 'Siswa Lolos Verifikasi',
            'user_id' => $calon->id,
        ]);

        // Peran calon siswa otomatis naik menjadi siswa
        $this->assertDatabaseHas('users', [
            'id' => $calon->id,
            'role' => 'siswa',
        ]);

        // Audit trail tercatat
        $this->assertDatabaseHas('sync_log', [
            'pendaftar_id' => $pendaftar->id,
            'status' => 'BERHASIL',
        ]);
    }

    public function test_batch_sync_processes_all_eligible_candidates(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status_aktif' => true]);

        // Create 3 candidates with LULUS status
        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->create(['role' => 'calon_siswa']);
            $p = Pendaftar::create([
                'user_id' => $user->id,
                'periode_id' => $this->periode->id,
                'no_pendaftaran' => "PPDB-2026-001{$i}",
                'nisn' => "00998877{$i}0",
                'nama_lengkap' => "Calon Siswa {$i}",
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Lampung',
                'tanggal_lahir' => '2010-01-01',
                'agama' => 'Islam',
                'asal_sekolah' => 'SMPN 1 TB',
                'alamat' => 'Jl. Sudirman',
                'no_hp' => "0812345678{$i}0",
                'status_pendaftaran' => 'lulus',
            ]);
            HasilSeleksi::create([
                'pendaftar_id' => $p->id,
                'status' => 'LULUS',
                'diverifikasi_oleh' => $admin->id,
            ]);
        }

        $response = $this->actingAs($admin)->post('/admin/sync/batch');
        $response->assertSessionHas('success');

        $this->assertEquals(3, Siswa::count());
        $this->assertEquals(3, SyncLog::where('status', 'BERHASIL')->count());
    }

    public function test_admin_can_view_sync_audit_trail_and_retry_sync(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status_aktif' => true]);
        $calon = User::factory()->create(['role' => 'calon_siswa']);

        $pendaftar = Pendaftar::create([
            'user_id' => $calon->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-9999',
            'nisn' => '0099998888',
            'nama_lengkap' => 'Siswa Retry Test',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lampung',
            'tanggal_lahir' => '2010-01-01',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Jl. Merdeka',
            'no_hp' => '081299998888',
            'status_pendaftaran' => 'lulus',
        ]);

        $log = SyncLog::create([
            'pendaftar_id' => $pendaftar->id,
            'siswa_id' => null,
            'user_id' => $admin->id,
            'waktu_sinkron' => now(),
            'status' => 'GAGAL',
            'detail_payload' => ['error' => 'Simulated error'],
            'error_message' => 'Simulated error',
        ]);

        // Access sync dashboard
        $response = $this->actingAs($admin)->get('/admin/sync');
        $response->assertStatus(200)
            ->assertSee('Audit Log Sinkronisasi PPDB')
            ->assertSee('GAGAL');

        // Retry sync
        $retryResponse = $this->actingAs($admin)->post("/admin/sync/{$log->id}/retry");
        $retryResponse->assertSessionHas('success');
    }
}
