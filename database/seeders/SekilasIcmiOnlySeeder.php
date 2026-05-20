<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SekilasIcmiOnlySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProfilePageSeeder::class,
        ]);
    }
}

