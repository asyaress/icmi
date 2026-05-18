<?php

namespace Database\Seeders\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()->where('slug', 'super-admin')->first();

        if (!$role) {
            return;
        }

        $email = (string) env('ICMI_ADMIN_EMAIL', 'admin@gmail.com');
        $password = (string) env('ICMI_ADMIN_PASSWORD', 'password');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin ICMI',
                'password' => $password,
                'role_id' => $role->id,
                'is_active' => true,
            ]
        );
    }
}
