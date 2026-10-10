<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $operator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'nama' => 'Admin Utama',
            'email' => 'admin.utama@sman1tb.sch.id',
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        $this->operator = User::factory()->create([
            'nama' => 'Operator SPMB',
            'email' => 'operator.spmb@sman1tb.sch.id',
            'role' => 'operator',
            'status_aktif' => true,
        ]);
    }

    public function test_admin_can_access_admin_management_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/admins');

        $response->assertStatus(200)
            ->assertSee('Kelola Akun Admin')
            ->assertSee('Buat Akun Admin / Operator Baru')
            ->assertSee('Admin Utama')
            ->assertSee('Operator SPMB');
    }

    public function test_operator_cannot_access_admin_management_page(): void
    {
        $response = $this->actingAs($this->operator)->get('/admin/admins');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_admin_successfully(): void
    {
        $response = $this->actingAs($this->superAdmin)->post('/admin/admins', [
            'nama' => 'Wakil Kurikulum Admin',
            'email' => 'kurikulum.admin@sman1tb.sch.id',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'nama' => 'Wakil Kurikulum Admin',
            'email' => 'kurikulum.admin@sman1tb.sch.id',
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        $created = User::where('email', 'kurikulum.admin@sman1tb.sch.id')->first();
        $this->assertNotNull($created);
        $this->assertTrue(Hash::check('password123', $created->password));
    }

    public function test_admin_can_create_new_operator_successfully(): void
    {
        $response = $this->actingAs($this->superAdmin)->post('/admin/admins', [
            'nama' => 'Staff TU SPMB',
            'email' => 'staff.tu@sman1tb.sch.id',
            'role' => 'operator',
            'password' => 'operator2026',
            'password_confirmation' => 'operator2026',
        ]);

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'nama' => 'Staff TU SPMB',
            'email' => 'staff.tu@sman1tb.sch.id',
            'role' => 'operator',
            'status_aktif' => true,
        ]);
    }

    public function test_operator_cannot_create_admin_account(): void
    {
        $response = $this->actingAs($this->operator)->post('/admin/admins', [
            'nama' => 'Illegal Admin',
            'email' => 'illegal@sman1tb.sch.id',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'illegal@sman1tb.sch.id']);
    }

    public function test_validation_prevents_duplicate_email_or_invalid_password(): void
    {
        // 1. Duplicate email
        $responseDuplicate = $this->actingAs($this->superAdmin)->post('/admin/admins', [
            'nama' => 'Duplikat Admin',
            'email' => $this->superAdmin->email,
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $responseDuplicate->assertSessionHasErrors('email');

        // 2. Password mismatch
        $responseMismatch = $this->actingAs($this->superAdmin)->post('/admin/admins', [
            'nama' => 'Mismatch Password',
            'email' => 'mismatch@sman1tb.sch.id',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);
        $responseMismatch->assertSessionHasErrors('password');

        // 3. Password too short (< 8 chars)
        $responseShort = $this->actingAs($this->superAdmin)->post('/admin/admins', [
            'nama' => 'Short Password',
            'email' => 'short@sman1tb.sch.id',
            'role' => 'admin',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);
        $responseShort->assertSessionHasErrors('password');
    }

    public function test_admin_can_toggle_status_of_other_admin(): void
    {
        $targetUser = User::factory()->create([
            'nama' => 'Admin Cabang',
            'email' => 'cabang@sman1tb.sch.id',
            'role' => 'admin',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->superAdmin)->post("/admin/admins/{$targetUser->id}/toggle");
        $response->assertSessionHas('success');

        $this->assertFalse($targetUser->fresh()->status_aktif);

        // Toggle back to active
        $response2 = $this->actingAs($this->superAdmin)->post("/admin/admins/{$targetUser->id}/toggle");
        $response2->assertSessionHas('success');
        $this->assertTrue($targetUser->fresh()->status_aktif);
    }

    public function test_admin_cannot_toggle_or_delete_own_account(): void
    {
        // 1. Cannot toggle own account
        $responseToggle = $this->actingAs($this->superAdmin)->post("/admin/admins/{$this->superAdmin->id}/toggle");
        $responseToggle->assertSessionHas('error');
        $this->assertTrue($this->superAdmin->fresh()->status_aktif);

        // 2. Cannot delete own account
        $responseDelete = $this->actingAs($this->superAdmin)->delete("/admin/admins/{$this->superAdmin->id}");
        $responseDelete->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
    }

    public function test_admin_can_reset_password_of_other_admin(): void
    {
        $targetUser = User::factory()->create([
            'nama' => 'Admin Target',
            'email' => 'target@sman1tb.sch.id',
            'role' => 'admin',
            'password' => Hash::make('old_password'),
        ]);

        $response = $this->actingAs($this->superAdmin)->post("/admin/admins/{$targetUser->id}/password", [
            'password' => 'new_secret_password',
            'password_confirmation' => 'new_secret_password',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('new_secret_password', $targetUser->fresh()->password));
    }

    public function test_admin_can_delete_other_admin(): void
    {
        $targetUser = User::factory()->create([
            'nama' => 'Admin Hapus',
            'email' => 'hapus@sman1tb.sch.id',
            'role' => 'operator',
        ]);

        $response = $this->actingAs($this->superAdmin)->delete("/admin/admins/{$targetUser->id}");
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    public function test_admin_and_operator_can_view_all_sso_users(): void
    {
        $responseAdmin = $this->actingAs($this->superAdmin)->get('/admin/users');
        $responseAdmin->assertStatus(200)
            ->assertSee('Manajemen Akun Pengguna (SSO)')
            ->assertSee('Admin Utama')
            ->assertSee('Operator SPMB');

        $responseOperator = $this->actingAs($this->operator)->get('/admin/users');
        $responseOperator->assertStatus(200)
            ->assertSee('Manajemen Akun Pengguna (SSO)');
    }
}
