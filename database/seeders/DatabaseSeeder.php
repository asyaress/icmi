<?php

namespace Database\Seeders;

use Database\Seeders\Admin\AdminUserSeeder;
use Database\Seeders\Admin\RolePermissionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            InfoMediaCategorySeeder::class,
            OpiniTokohCategorySeeder::class,
            ProfilePageSeeder::class,
            ProfessionalDummySeeder::class,
            EventDummySeeder::class,
        ]);
    }
}
