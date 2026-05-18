<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class InfoMediaCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::query()->updateOrCreate(
            ['slug' => 'siaran-pers'],
            [
                'name' => 'Siaran Pers',
                'description' => 'Publikasi resmi dan siaran pers ICMI.',
                'is_active' => true,
            ]
        );

        Category::query()->updateOrCreate(
            ['slug' => 'kabar-icmi'],
            [
                'name' => 'Kabar ICMI',
                'description' => 'Informasi, kegiatan, dan update internal ICMI.',
                'is_active' => true,
            ]
        );
    }
}
