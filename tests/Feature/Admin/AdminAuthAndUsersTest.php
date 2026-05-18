<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\Admin\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthAndUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_login_page_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSee('Admin ICMI Kaltim');
    }

    public function test_super_admin_can_login_and_access_dashboard(): void
    {
        $role = Role::query()->where('slug', 'super-admin')->firstOrFail();

        User::query()->create([
            'name' => 'Super Admin',
            'email' => 'super@icmi.test',
            'password' => 'password123',
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'super@icmi.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $dashboardResponse = $this->get('/admin');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Dashboard');
    }

    public function test_admin_can_create_new_admin_user(): void
    {
        $superRole = Role::query()->where('slug', 'super-admin')->firstOrFail();
        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $user = User::query()->create([
            'name' => 'Super Admin',
            'email' => 'super2@icmi.test',
            'password' => 'password123',
            'role_id' => $superRole->id,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('admin.users.store'), [
                'name' => 'Editor Admin',
                'email' => 'editor@icmi.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role_id' => $adminRole->id,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'editor@icmi.test',
            'role_id' => $adminRole->id,
            'is_active' => 1,
        ]);
    }

    public function test_login_is_rate_limited_after_too_many_attempts(): void
    {
        $role = Role::query()->where('slug', 'super-admin')->firstOrFail();

        User::query()->create([
            'name' => 'Super Admin',
            'email' => 'ratelimit@icmi.test',
            'password' => 'password123',
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'ratelimit@icmi.test',
                'password' => 'salah-password',
            ])->assertSessionHasErrors('email');
        }

        $this->post('/admin/login', [
            'email' => 'ratelimit@icmi.test',
            'password' => 'salah-password',
        ])->assertStatus(429);
    }
}
