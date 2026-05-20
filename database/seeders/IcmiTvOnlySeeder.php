<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IcmiTvOnlySeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('email', 'admin@gmail.com')->first()
            ?? User::query()->first();

        if (! $author) {
            return;
        }

        $items = [
            [
                'title' => 'ICMI Kaltim Mantapkan Konsolidasi Jelang Pelantikan',
                'youtube_url' => 'https://www.youtube.com/watch?v=Eb6_8xeb9-k',
                'youtube_id' => 'Eb6_8xeb9-k',
                'description' => 'Liputan ICMI Kaltim terkait penguatan konsolidasi organisasi menjelang pelantikan pengurus.',
                'published_at' => '2026-05-13 10:00:00',
            ],
            [
                'title' => 'ICMI Kaltim Perluas Aksi Sosial Lewat Cek Kesehatan Gratis di CFD Samarinda',
                'youtube_url' => 'https://youtube.com/shorts/4G3TmJFQ8-E?feature=share',
                'youtube_id' => '4G3TmJFQ8-E',
                'description' => 'Dokumentasi ICMI TV untuk aksi sosial cek kesehatan gratis ICMI Kaltim di kawasan CFD Samarinda.',
                'published_at' => '2026-05-10 10:00:00',
            ],
        ];

        foreach ($items as $item) {
            Video::query()->updateOrCreate(
                ['youtube_id' => $item['youtube_id']],
                [
                    'author_id' => $author->id,
                    'title' => $item['title'],
                    'slug' => Str::slug($item['title']),
                    'description' => $item['description'],
                    'seo_title' => $item['title'],
                    'seo_description' => Str::limit($item['description'], 155, ''),
                    'youtube_url' => $item['youtube_url'],
                    'status' => 'published',
                    'published_at' => $item['published_at'],
                ]
            );
        }
    }
}

