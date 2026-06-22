<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class IcmiNewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::query()->updateOrCreate(
            ['slug' => Category::ICMI_DAERAH_SLUG],
            [
                'name' => 'ICMI Daerah',
                'description' => 'Berita kegiatan dan perkembangan ICMI di daerah.',
                'is_active' => true,
            ],
        );

        Category::query()->updateOrCreate(
            ['slug' => Category::ICMI_PUSAT_SLUG],
            [
                'name' => 'ICMI Pusat',
                'description' => 'Berita dan informasi resmi dari ICMI Pusat.',
                'is_active' => true,
            ],
        );
    }
}
