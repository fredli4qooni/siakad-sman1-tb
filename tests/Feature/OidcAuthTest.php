<?php

namespace Tests\Feature;

use App\Models\PeriodePpdb;
use App\Models\User;
use App\Services\OidcService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OidcAuthTest extends TestCase
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

    public function test_oidc_discovery_endpoint_returns_valid_configuration(): void
    {
        $response = $this->getJson('/.well-known/openid-configuration');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'issuer',
                'authorization_endpoint',
                'token_endpoint',
                'userinfo_endpoint',
                'response_types_supported',
                'id_token_signing_alg_values_supported',
                'scopes_supported',
            ]);
    }

    public function test_user_can_login_via_auth_login_endpoint(): void
    {
        $user = User::factory()->create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status_aktif' => true,
        ]);

        $response = $this->post('/auth/login', [
            'identity' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('siakad.siswa.kelas'));
    }

    public function test_user_cannot_login_with_incorrect_password(): void
    {
        User::factory()->create([
            'email' => 'budi@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from('/auth/login')->post('/auth/login', [
            'identity' => 'budi@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/auth/login');
        $response->assertSessionHasErrors('identity');
    }

    public function test_calon_siswa_can_register_new_account(): void
    {
        $response = $this->post('/auth/register', [
            'nama' => 'Calon Siswa Baru',
            'nisn' => '0098765432',
            'email' => 'calon@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'calon@example.com',
            'role' => 'calon_siswa',
        ]);

        $this->assertDatabaseHas('pendaftar', [
            'nisn' => '0098765432',
            'nama_lengkap' => 'Calon Siswa Baru',
        ]);

        $response->assertRedirect(route('pendaftar.formulir'));
    }

    public function test_calon_siswa_registration_generates_unique_no_pendaftaran_even_with_deleted_records(): void
    {
        // Buat data pendaftar awal dengan nomor urut lebih tinggi (misal setelah pendaftar sebelumnya dihapus)
        $userAwal = User::factory()->create(['role' => 'calon_siswa']);
        $pendaftarAwal = \App\Models\Pendaftar::create([
            'user_id' => $userAwal->id,
            'periode_id' => $this->periode->id,
            'no_pendaftaran' => 'SPMB-' . date('Y') . '-0004',
            'nisn' => '0011223399',
            'nama_lengkap' => 'Siswa Eksis 4',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lampung',
            'tanggal_lahir' => '2010-01-01',
            'asal_sekolah' => 'SMPN 1 TB',
            'alamat' => 'Alamat',
            'no_hp' => '0812345678',
            'status_pendaftaran' => 'draft',
        ]);

        // Total data di database saat ini hanya 1, tetapi nomor pendaftarannya sudah berakhiran 0004.
        // Jika menggunakan count() + 1, sistem lama akan mencoba membuat SPMB-YYYY-0002 yang berpotensi bentrok jika ada record lain.
        // Lebih parah lagi, jika ada SPMB-YYYY-0002 dan count adalah 1, sistem lama bentrok pada 0002.
        // Sekarang registrasi calon siswa baru:
        $response = $this->post('/auth/register', [
            'nama' => 'Calon Siswa Baru Unik',
            'nisn' => '0098765439',
            'email' => 'calon.unik@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('pendaftar.formulir'));

        // Pendaftar baru harus mendapatkan nomor urut di atas 0004 (misal 0005) tanpa error constraint violation
        $this->assertDatabaseHas('pendaftar', [
            'nisn' => '0098765439',
            'nama_lengkap' => 'Calon Siswa Baru Unik',
            'no_pendaftaran' => 'SPMB-' . date('Y') . '-0005',
        ]);
    }

    public function test_oidc_authorization_flow_and_token_exchange(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin Sekolah',
            'email' => 'admin@sman1tb.sch.id',
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        $this->actingAs($user);

        // 1. Authorize Request
        $redirectUri = url('/ppdb/sso/callback');
        $response = $this->get('/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => 'ppdb_client',
            'redirect_uri' => $redirectUri,
            'scope' => 'openid profile email role',
            'state' => 'test-state-123',
        ]));

        $response->assertStatus(302);
        $targetUrl = $response->headers->get('Location');
        $this->assertStringContainsString('code=', $targetUrl);
        $this->assertStringContainsString('state=test-state-123', $targetUrl);

        // Extract code
        parse_str(parse_url($targetUrl, PHP_URL_QUERY), $queryParams);
        $code = $queryParams['code'];
        $this->assertNotEmpty($code);

        // 2. Token Exchange Request
        $tokenResponse = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => 'ppdb_client',
            'client_secret' => config('oidc.clients.ppdb_client.client_secret'),
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ]);

        $tokenResponse->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
                'id_token',
                'scope',
            ]);

        $idToken = $tokenResponse->json('id_token');
        $accessToken = $tokenResponse->json('access_token');

        // 3. Verify ID Token claims using OidcService
        /** @var OidcService $oidcService */
        $oidcService = app(OidcService::class);
        $claims = $oidcService->verifyIdToken($idToken, 'ppdb_client');

        $this->assertNotNull($claims);
        $this->assertEquals((string) $user->id, $claims['sub']);
        $this->assertEquals($user->nama, $claims['nama']);
        $this->assertEquals('admin', $claims['role']);

        // 4. Userinfo Request
        $userinfoResponse = $this->withHeader('Authorization', 'Bearer ' . $accessToken)
            ->getJson('/oauth/userinfo');

        $userinfoResponse->assertStatus(200)
            ->assertJson([
                'sub' => (string) $user->id,
                'email' => 'admin@sman1tb.sch.id',
                'role' => 'admin',
            ]);
    }

    public function test_role_middleware_blocks_unauthorized_access(): void
    {
        $guru = User::factory()->create([
            'role' => 'guru',
            'status_aktif' => true,
        ]);

        // Guru tidak berhak mengakses admin dashboard
        $this->actingAs($guru)
            ->get('/admin/dashboard')
            ->assertStatus(403);

        // Guru berhak mengakses guru dashboard
        $this->actingAs($guru)
            ->get('/guru/dashboard')
            ->assertStatus(200);
    }

    public function test_single_logout_clears_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/auth/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
