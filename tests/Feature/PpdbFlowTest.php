<?php

namespace Tests\Feature;

use App\Models\BerkasPendaftaran;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbFlowTest extends TestCase
{
    use RefreshDatabase;

    protected PeriodePpdb $periode;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->periode = PeriodePpdb::create([
            'tahun_ajaran' => '2026/2027',
            'nama_gelombang' => 'Gelombang 1 Reguler',
            'tanggal_buka' => '2026-07-01',
            'tanggal_tutup' => '2026-07-15',
            'kuota' => 540,
            'is_aktif' => true,
        ]);
    }

    public function test_public_landing_and_alur_pages_render_correctly(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertSee('PPDB 2026/2027')
            ->assertSee('540');

        $responseAlur = $this->get('/ppdb/alur');
        $responseAlur->assertStatus(200)
            ->assertSee('Alur Pendaftaran')
            ->assertSee('540 Siswa');
    }

    public function test_public_pengumuman_search(): void
    {
        $user = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0099',
            'nisn' => '0099887766',
            'nama_lengkap' => 'Siswa Seleksi Terdaftar',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandar Lampung',
            'tanggal_lahir' => '2010-05-12',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 Terbanggi Besar',
            'alamat' => 'Jl. Merdeka No. 12',
            'no_hp' => '081234567890',
            'status_pendaftaran' => 'terverifikasi',
        ]);

        $response = $this->get('/ppdb/pengumuman?keyword=0099887766');
        $response->assertStatus(200)
            ->assertSee('Siswa Seleksi Terdaftar')
            ->assertSee('PPDB-2026-0099');
    }

    public function test_candidate_can_fill_and_save_formulir(): void
    {
        $user = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0001',
            'nisn' => '0011223344',
            'nama_lengkap' => $user->nama,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => '-',
            'tanggal_lahir' => '2010-01-01',
            'agama' => 'Islam',
            'asal_sekolah' => '-',
            'alamat' => '-',
            'no_hp' => '-',
            'status_pendaftaran' => 'draft',
        ]);

        $response = $this->actingAs($user)->post('/pendaftar/formulir', [
            'nisn' => '0011223344',
            'nik' => '1802010101100001',
            'nama_lengkap' => 'Ahmad Santoso',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lampung Tengah',
            'tanggal_lahir' => '2010-03-15',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 2 Terbanggi Besar',
            'alamat' => 'Jl. Lintas Sumatera RT 02 RW 01',
            'no_hp' => '081398765432',
            'nama_ayah' => 'Bambang Santoso',
            'pekerjaan_ayah' => 'Wiraswasta',
            'nama_ibu' => 'Siti Aminah',
            'pekerjaan_ibu' => 'Guru',
            'no_hp_ortu' => '081211223344',
        ]);

        $response->assertRedirect(route('pendaftar.berkas'));

        $this->assertDatabaseHas('pendaftar', [
            'id' => $pendaftar->id,
            'nik' => '1802010101100001',
            'nama_lengkap' => 'Ahmad Santoso',
            'asal_sekolah' => 'SMPN 2 Terbanggi Besar',
        ]);

        $this->assertDatabaseHas('orang_tua', [
            'pendaftar_id' => $pendaftar->id,
            'nama_ayah' => 'Bambang Santoso',
            'nama_ibu' => 'Siti Aminah',
        ]);
    }

    public function test_candidate_can_upload_and_preview_documents(): void
    {
        $user = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0002',
            'nisn' => '0022334455',
            'nama_lengkap' => 'Dewi Sartika',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Terbanggi Besar',
            'tanggal_lahir' => '2010-06-20',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Jl. Pattimura No. 5',
            'no_hp' => '081299887766',
            'status_pendaftaran' => 'draft',
        ]);

        $file = UploadedFile::fake()->create('kartu_keluarga.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post('/pendaftar/berkas/upload', [
            'jenis_berkas' => 'kartu_keluarga',
            'file_berkas' => $file,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('berkas_pendaftaran', [
            'pendaftar_id' => $pendaftar->id,
            'jenis_berkas' => 'kartu_keluarga',
            'nama_file_asli' => 'kartu_keluarga.pdf',
        ]);

        $berkas = BerkasPendaftaran::where('pendaftar_id', $pendaftar->id)->first();
        Storage::disk('public')->assertExists($berkas->file_path);

        // Test preview
        $previewResponse = $this->actingAs($user)->get("/pendaftar/berkas/{$berkas->id}/preview");
        $previewResponse->assertStatus(200);
    }

    public function test_candidate_can_submit_for_verification_when_all_documents_uploaded(): void
    {
        $user = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0003',
            'nisn' => '0033445566',
            'nama_lengkap' => 'Rian Hidayat',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lampung',
            'tanggal_lahir' => '2010-08-10',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Jl. Ahmad Yani',
            'no_hp' => '081377889900',
            'status_pendaftaran' => 'draft',
        ]);

        // Upload 4 required documents
        foreach (['kartu_keluarga', 'akta_kelahiran', 'ijazah_skl', 'rapor'] as $jenis) {
            BerkasPendaftaran::create([
                'pendaftar_id' => $pendaftar->id,
                'jenis_berkas' => $jenis,
                'nama_file_asli' => "{$jenis}.pdf",
                'file_path' => "berkas_ppdb/{$pendaftar->id}/{$jenis}.pdf",
                'ukuran_file' => 200000,
                'mime_type' => 'application/pdf',
                'status_verifikasi' => 'menunggu',
            ]);
        }

        $response = $this->actingAs($user)->post('/pendaftar/berkas/kirim');
        $response->assertRedirect(route('pendaftar.dashboard'));

        $this->assertDatabaseHas('pendaftar', [
            'id' => $pendaftar->id,
            'status_pendaftaran' => 'menunggu_verifikasi',
        ]);
    }

    public function test_candidate_dashboard_and_printable_proof_are_accessible(): void
    {
        $user = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $user->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0004',
            'nisn' => '0044556677',
            'nama_lengkap' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Metro',
            'tanggal_lahir' => '2010-09-12',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 3 Metro',
            'alamat' => 'Jl. Sudirman',
            'no_hp' => '081234567891',
            'status_pendaftaran' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($user)->get('/pendaftar/dashboard');
        $response->assertStatus(200)
            ->assertSee('PPDB-2026-0004')
            ->assertSee('Siti Nurhaliza');

        $printResponse = $this->actingAs($user)->get('/pendaftar/cetak-bukti');
        $printResponse->assertStatus(200)
            ->assertSee('TANDA BUKTI PENDAFTARAN PPDB')
            ->assertSee('PPDB-2026-0004');
    }

    public function test_admin_can_manage_periode_and_verify_documents_and_set_kelulusan(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status_aktif' => true]);

        // 1. Admin checks periode list
        $responsePeriode = $this->actingAs($admin)->get('/admin/ppdb/periode');
        $responsePeriode->assertStatus(200)->assertSee('Gelombang 1 Reguler');

        // 2. Admin creates a new periode
        $this->actingAs($admin)->post('/admin/ppdb/periode', [
            'tahun_ajaran' => '2027/2028',
            'nama_gelombang' => 'Gelombang 1 Reguler 2027',
            'tanggal_buka' => '2027-07-01',
            'tanggal_tutup' => '2027-07-15',
            'kuota' => 600,
            'is_aktif' => 1,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('periode_ppdb', [
            'tahun_ajaran' => '2027/2028',
            'kuota' => 600,
            'is_aktif' => true,
        ]);

        // 3. Admin verifies candidate documents
        $calonUser = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftar = Pendaftar::create([
            'user_id' => $calonUser->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'PPDB-2026-0005',
            'nisn' => '0055667788',
            'nama_lengkap' => 'Eko Prasetyo',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandar Jaya',
            'tanggal_lahir' => '2010-11-20',
            'agama' => 'Islam',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Jl. Proklamasi No. 10',
            'no_hp' => '081299001122',
            'status_pendaftaran' => 'menunggu_verifikasi',
        ]);

        $berkas = BerkasPendaftaran::create([
            'pendaftar_id' => $pendaftar->id,
            'jenis_berkas' => 'kartu_keluarga',
            'nama_file_asli' => 'kk.pdf',
            'file_path' => 'berkas_ppdb/5/kk.pdf',
            'status_verifikasi' => 'menunggu',
        ]);

        $this->actingAs($admin)->post("/admin/ppdb/pendaftar/{$pendaftar->id}/verifikasi/{$berkas->id}", [
            'status_verifikasi' => 'valid',
            'catatan' => 'Dokumen asli sesuai data',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('berkas_pendaftaran', [
            'id' => $berkas->id,
            'status_verifikasi' => 'valid',
        ]);

        // 4. Admin decides selection outcome
        $this->actingAs($admin)->post("/admin/ppdb/seleksi/{$pendaftar->id}", [
            'status' => 'LULUS',
            'catatan' => 'Memenuhi kuota zonasi murni',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('hasil_seleksi', [
            'pendaftar_id' => $pendaftar->id,
            'status' => 'LULUS',
        ]);

        $this->assertDatabaseHas('pendaftar', [
            'id' => $pendaftar->id,
            'status_pendaftaran' => 'lulus',
        ]);
    }
}
