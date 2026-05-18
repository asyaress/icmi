<?php

namespace Database\Seeders\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Dashboard', 'slug' => 'manage-dashboard'],
            ['name' => 'Manage Users', 'slug' => 'manage-users'],
            ['name' => 'Manage Content', 'slug' => 'manage-content'],
            ['name' => 'Publish Content', 'slug' => 'publish-content'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['name'],
                ]
            );
        }

        $superAdmin = Role::query()->updateOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Akses penuh sistem']
        );

        $admin = Role::query()->updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Kelola konten dan user admin']
        );

        $editor = Role::query()->updateOrCreate(
            ['slug' => 'editor'],
            ['name' => 'Editor', 'description' => 'Kelola dan review konten']
        );

        $contributor = Role::query()->updateOrCreate(
            ['slug' => 'contributor'],
            ['name' => 'Kontributor', 'description' => 'Buat draft konten']
        );

        $allPermissionIds = Permission::query()->pluck('id')->all();
        $contentPermissionIds = Permission::query()->whereIn('slug', ['manage-dashboard', 'manage-content', 'publish-content'])->pluck('id')->all();
        $contributorPermissionIds = Permission::query()->whereIn('slug', ['manage-dashboard', 'manage-content'])->pluck('id')->all();

        $superAdmin->permissions()->sync($allPermissionIds);
        $admin->permissions()->sync($allPermissionIds);
        $editor->permissions()->sync($contentPermissionIds);
        $contributor->permissions()->sync($contributorPermissionIds);
    }
}
