<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class OpiniTokohCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::query()->updateOrCreate(
            ['slug' => 'opini'],
            [
                'name' => 'Opini',
                'description' => 'Artikel opini ICMI.',
                'is_active' => true,
            ]
        );

        Category::query()->updateOrCreate(
            ['slug' => 'tokoh'],
            [
                'name' => 'Tokoh',
                'description' => 'Profil dan pemikiran tokoh.',
                'is_active' => true,
            ]
        );
    }
}
