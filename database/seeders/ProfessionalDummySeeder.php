<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\HomeSettingController;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfessionalDummySeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('email', 'admin@gmail.com')->first() ?? User::query()->first();
        if (! $author) {
            return;
        }

        $this->seedEditorialUsers();
        $this->seedHomeSettings();

        $newsCategories = $this->seedNewsCategories();
        $mediaCategories = $this->seedMediaCategories();
        $opinionCategories = $this->seedOpinionCategories();
        $tags = $this->seedTags();

        $imagePool = $this->prepareImagePool();
        $newsPosts = $this->seedNewsPosts($author->id, $newsCategories, $tags, $imagePool);
        $this->seedMediaInfoPosts($author->id, $mediaCategories, $tags, $imagePool);
        $this->seedOpinionTokohPosts($author->id, $opinionCategories, $tags, $imagePool);

        $this->seedGalleries($author->id, $imagePool);
        $this->seedVideos($author->id, $imagePool);

        if ($newsPosts->isNotEmpty()) {
            Setting::set('home_hero_limit', '4', 'home');
            Setting::set('home_trending_limit', '8', 'home');
            Setting::set('home_featured_limit', '5', 'home');
            Setting::set('home_video_limit', '6', 'home');
            Setting::set('home_trending_today_limit', '5', 'home');
            Setting::set('home_main_posts_limit', '9', 'home');
        }
    }

    private function seedEditorialUsers(): void
    {
        $users = [
            ['name' => 'Admin Redaksi ICMI', 'email' => 'redaksi@icmikaltim.or.id', 'role' => 'admin'],
            ['name' => 'Editor Konten ICMI', 'email' => 'editor@icmikaltim.or.id', 'role' => 'editor'],
            ['name' => 'Kontributor ICMI', 'email' => 'kontributor@icmikaltim.or.id', 'role' => 'contributor'],
        ];

        foreach ($users as $user) {
            $roleId = Role::query()->where('slug', $user['role'])->value('id');
            if (! $roleId) {
                continue;
            }

            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => 'password',
                    'role_id' => $roleId,
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedHomeSettings(): void
    {
        $settings = array_merge(HomeSettingController::DEFAULTS, [
            'site_name' => 'ICMI Kaltim',
            'site_tagline' => 'Kolaborasi Cendekiawan Muslim untuk Kalimantan Timur Maju',
            'meta_default_description' => 'Portal resmi ICMI Kaltim berisi berita strategis, opini tokoh, info media, galeri kegiatan, dan program ICMI TV.',
            'home_show_hero' => '1',
            'home_show_trending' => '1',
            'home_show_featured' => '1',
            'home_show_video' => '1',
            'home_show_trending_today' => '1',
            'home_show_main_posts' => '1',
        ]);

        foreach ($settings as $key => $value) {
            Setting::set($key, (string) $value, 'home');
        }
    }

    /**
     * @return array<string, int>
     */
    private function seedNewsCategories(): array
    {
        $items = [
            ['name' => 'Kebijakan Publik', 'slug' => 'kebijakan-publik', 'description' => 'Analisis kebijakan dan isu strategis daerah.'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'description' => 'Program penguatan SDM dan pendidikan masyarakat.'],
            ['name' => 'Ekonomi Umat', 'slug' => 'ekonomi-umat', 'description' => 'Pemberdayaan ekonomi, UMKM, dan kewirausahaan.'],
            ['name' => 'Sains & Teknologi', 'slug' => 'sains-teknologi', 'description' => 'Inovasi teknologi dan transformasi digital.'],
            ['name' => 'Sosial Kemanusiaan', 'slug' => 'sosial-kemanusiaan', 'description' => 'Kegiatan sosial dan pelayanan kemasyarakatan.'],
        ];

        $ids = [];
        foreach ($items as $item) {
            $category = Category::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'is_active' => true,
                ]
            );
            $ids[$item['slug']] = $category->id;
        }

        return $ids;
    }

    /**
     * @return array<string, int>
     */
    private function seedMediaCategories(): array
    {
        $slugs = ['siaran-pers', 'kabar-icmi'];
        $ids = [];
        foreach ($slugs as $slug) {
            $id = Category::query()->where('slug', $slug)->value('id');
            if ($id) {
                $ids[$slug] = (int) $id;
            }
        }

        return $ids;
    }

    /**
     * @return array<string, int>
     */
    private function seedOpinionCategories(): array
    {
        $slugs = ['opini', 'tokoh'];
        $ids = [];
        foreach ($slugs as $slug) {
            $id = Category::query()->where('slug', $slug)->value('id');
            if ($id) {
                $ids[$slug] = (int) $id;
            }
        }

        return $ids;
    }

    /**
     * @return array<string, int>
     */
    private function seedTags(): array
    {
        $tagNames = [
            'Kaltim Maju',
            'Pendidikan Umat',
            'Transformasi Digital',
            'UMKM Halal',
            'Kepemimpinan',
            'Kesehatan Publik',
            'Kolaborasi Daerah',
            'Pemuda Cendekia',
            'Ekonomi Syariah',
            'Aksi Sosial',
        ];

        $ids = [];
        foreach ($tagNames as $name) {
            $tag = Tag::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $ids[$tag->slug] = $tag->id;
        }

        return $ids;
    }

    /**
     * @return array<int, string>
     */
    private function prepareImagePool(): array
    {
        $sources = [
            'public/WhatsApp Image 2026-05-17 at 21.49.07.jpeg',
            'public/assets/images/main-post-thumb-1.jpg',
            'public/assets/images/main-post-thumb-2.jpg',
            'public/assets/images/main-post-thumb-3.jpg',
            'public/assets/images/main-post-thumb-4.jpg',
            'public/assets/images/main-post-thumb-5.jpg',
            'public/assets/images/main-post-thumb-6.jpg',
            'public/assets/images/latest-post-1.jpg',
            'public/assets/images/latest-post-2.jpg',
            'public/assets/images/latest-post-3.jpg',
            'public/assets/images/latest-post-4.jpg',
            'public/assets/images/featured-trending-thumb-1.jpg',
            'public/assets/images/featured-trending-thumb-2.jpg',
            'public/assets/images/featured-trending-thumb-3.jpg',
            'public/assets/images/feature-news-thuimb.jpg',
            'public/assets/images/trending-news-list-thumb-1.jpg',
            'public/assets/images/trending-news-list-thumb-2.jpg',
            'public/assets/images/trending-news-list-thumb-3.jpg',
            'public/assets/images/trending-news-list-thumb-4.jpg',
            'public/assets/images/author-item-1.jpg',
            'public/assets/images/author-item-2.jpg',
            'public/assets/images/author-item-3.jpg',
            'public/assets/images/author-item-4.jpg',
            'public/assets/images/author-item-5.jpg',
            'public/assets/images/author-item-6.jpg',
        ];

        $copied = [];
        foreach ($sources as $source) {
            $absolute = base_path($source);
            if (! is_file($absolute)) {
                continue;
            }

            $target = 'dummy/professional/' . basename($source);
            Storage::disk('public')->put($target, file_get_contents($absolute));
            $copied[] = $target;
        }

        if (empty($copied)) {
            return ['dummy/icmi-cfd-2026.jpeg'];
        }

        return $copied;
    }

    /**
     * @param array<string, int> $categories
     * @param array<string, int> $tags
     * @param array<int, string> $imagePool
     */
    private function seedNewsPosts(int $authorId, array $categories, array $tags, array $imagePool): \Illuminate\Support\Collection
    {
        $articles = [
            ['title' => 'ICMI Kaltim Dorong Peta Jalan SDM Unggul Berbasis Masjid Kampus', 'category' => 'pendidikan'],
            ['title' => 'Forum Cendekia Kaltim Rumuskan Rekomendasi Strategis Ibu Kota Nusantara', 'category' => 'kebijakan-publik'],
            ['title' => 'Program Inkubasi UMKM Halal ICMI Kaltim Resmi Dimulai di Samarinda', 'category' => 'ekonomi-umat'],
            ['title' => 'Literasi AI untuk Guru Madrasah Jadi Prioritas Program 2026', 'category' => 'sains-teknologi'],
            ['title' => 'Sinergi Lintas Ormas untuk Layanan Kesehatan Preventif Warga', 'category' => 'sosial-kemanusiaan'],
            ['title' => 'ICMI Kaltim Rilis Kajian Ketahanan Pangan Kawasan Pesisir', 'category' => 'kebijakan-publik'],
            ['title' => 'Pelatihan Konten Dakwah Digital Menjangkau 10 Kabupaten Kota', 'category' => 'sains-teknologi'],
            ['title' => 'Beasiswa Cendekia Muda ICMI Menyasar Mahasiswa Berprestasi Daerah', 'category' => 'pendidikan'],
            ['title' => 'Klinik Bisnis Syariah Bantu UMKM Naik Kelas dan Siap Ekspor', 'category' => 'ekonomi-umat'],
            ['title' => 'Gerakan Seribu Paket Gizi untuk Keluarga Prasejahtera Kaltim', 'category' => 'sosial-kemanusiaan'],
            ['title' => 'Diskusi Publik: Menjaga Daya Saing Daerah di Era Industri Hijau', 'category' => 'kebijakan-publik'],
            ['title' => 'Komunitas Pemuda Cendekia Luncurkan Laboratorium Inovasi Sosial', 'category' => 'pendidikan'],
        ];

        $created = collect();

        foreach ($articles as $index => $article) {
            $categoryId = $categories[$article['category']] ?? null;
            if (! $categoryId) {
                continue;
            }

            $publishedAt = now()->subDays(2 + ($index * 2))->setHour(9 + ($index % 6))->setMinute(15);
            $title = $article['title'];

            $post = Post::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'category_id' => $categoryId,
                    'author_id' => $authorId,
                    'type' => Post::TYPE_NEWS,
                    'title' => $title,
                    'excerpt' => $this->buildExcerpt($title),
                    'content' => $this->buildArticleHtml($title, 'news'),
                    'seo_title' => $title . ' | ICMI Kaltim',
                    'seo_description' => $this->buildExcerpt($title),
                    'featured_image' => $imagePool[$index % count($imagePool)] ?? null,
                    'status' => 'published',
                    'published_at' => $publishedAt,
                ]
            );

            $tagIds = collect(array_values($tags))->slice($index % max(1, count($tags) - 2), 3)->values()->all();
            if (! empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }

            $created->push($post);
        }

        return $created;
    }

    /**
     * @param array<string, int> $categories
     * @param array<string, int> $tags
     * @param array<int, string> $imagePool
     */
    private function seedMediaInfoPosts(int $authorId, array $categories, array $tags, array $imagePool): void
    {
        $entries = [
            ['title' => 'Siaran Pers: ICMI Kaltim Tegaskan Komitmen Pembangunan SDM Inklusif', 'category' => 'siaran-pers'],
            ['title' => 'Kabar ICMI: Rakor Wilayah Bahas Agenda Strategis Semester II', 'category' => 'kabar-icmi'],
            ['title' => 'Siaran Pers: Kolaborasi ICMI dan Kampus untuk Riset Terapan Daerah', 'category' => 'siaran-pers'],
            ['title' => 'Kabar ICMI: Roadshow Literasi Keuangan Syariah di Balikpapan', 'category' => 'kabar-icmi'],
            ['title' => 'Siaran Pers: Program Cek Kesehatan Gratis Sasar Area Publik Kota', 'category' => 'siaran-pers'],
            ['title' => 'Kabar ICMI: Forum Tokoh Muda Bahas Kepemimpinan Transformasional', 'category' => 'kabar-icmi'],
        ];

        foreach ($entries as $index => $entry) {
            $categoryId = $categories[$entry['category']] ?? null;
            if (! $categoryId) {
                continue;
            }

            $title = $entry['title'];
            $post = Post::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'category_id' => $categoryId,
                    'author_id' => $authorId,
                    'type' => Post::TYPE_MEDIA_INFO,
                    'title' => $title,
                    'excerpt' => $this->buildExcerpt($title),
                    'content' => $this->buildArticleHtml($title, 'media'),
                    'seo_title' => $title . ' | Info Media ICMI Kaltim',
                    'seo_description' => $this->buildExcerpt($title),
                    'featured_image' => $imagePool[($index + 3) % count($imagePool)] ?? null,
                    'status' => 'published',
                    'published_at' => now()->subDays(1 + $index)->setHour(14)->setMinute(30),
                ]
            );

            $tagIds = collect(array_values($tags))->shuffle()->take(2)->values()->all();
            if (! empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        }
    }

    /**
     * @param array<string, int> $categories
     * @param array<string, int> $tags
     * @param array<int, string> $imagePool
     */
    private function seedOpinionTokohPosts(int $authorId, array $categories, array $tags, array $imagePool): void
    {
        $entries = [
            ['title' => 'Opini: Ekonomi Keumatan Perlu Akselerasi Digital yang Beretika', 'category' => 'opini'],
            ['title' => 'Opini: Perguruan Tinggi dan Ormas Harus Bersinergi Menyiapkan Talenta', 'category' => 'opini'],
            ['title' => 'Opini: Kesehatan Preventif sebagai Pilar Ketahanan Keluarga', 'category' => 'opini'],
            ['title' => 'Tokoh: Jimly Asshiddiqie tentang Etika Kepemimpinan Publik', 'category' => 'tokoh'],
            ['title' => 'Tokoh: Riri Fitri Sari Dorong Inovasi Pendidikan Berbasis Teknologi', 'category' => 'tokoh'],
            ['title' => 'Tokoh: Priyo Budi Santoso dan Diplomasi Gagasan Kebangsaan', 'category' => 'tokoh'],
            ['title' => 'Tokoh: Ilham Akbar Habibie Soroti Pentingnya Industri Berbasis Riset', 'category' => 'tokoh'],
            ['title' => 'Tokoh: Mohammad Jafar Hafsah Bicara Ketahanan Pangan Lokal', 'category' => 'tokoh'],
        ];

        foreach ($entries as $index => $entry) {
            $categoryId = $categories[$entry['category']] ?? null;
            if (! $categoryId) {
                continue;
            }

            $title = $entry['title'];
            $post = Post::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'category_id' => $categoryId,
                    'author_id' => $authorId,
                    'type' => Post::TYPE_OPINION,
                    'title' => $title,
                    'excerpt' => $this->buildExcerpt($title),
                    'content' => $this->buildArticleHtml($title, 'opinion'),
                    'seo_title' => $title . ' | Opini & Tokoh',
                    'seo_description' => $this->buildExcerpt($title),
                    'featured_image' => $imagePool[($index + 7) % count($imagePool)] ?? null,
                    'status' => 'published',
                    'published_at' => now()->subDays(3 + $index)->setHour(10)->setMinute(45),
                ]
            );

            $tagIds = collect(array_values($tags))->shuffle()->take(3)->values()->all();
            if (! empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        }
    }

    /**
     * @param array<int, string> $imagePool
     */
    private function seedGalleries(int $authorId, array $imagePool): void
    {
        $galleries = [
            'Dokumentasi Halalbihalal ICMI Kaltim 2026',
            'Workshop Dai Cendekia dan Literasi Publik',
            'Pemeriksaan Kesehatan Gratis di CFD Samarinda',
            'Forum Ekonomi Umat dan UMKM Halal',
            'Rapat Kerja Wilayah ICMI Kaltim',
        ];

        foreach ($galleries as $index => $title) {
            $gallery = Gallery::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'author_id' => $authorId,
                    'title' => $title,
                    'description' => 'Dokumentasi resmi kegiatan ' . $title . ' yang diselenggarakan ICMI Kaltim bersama mitra strategis.',
                    'seo_title' => $title . ' | Galeri ICMI Kaltim',
                    'seo_description' => 'Galeri foto kegiatan ' . $title . ' sebagai bagian dari program penguatan kontribusi sosial dan intelektual ICMI Kaltim.',
                    'cover_image' => $imagePool[($index + 11) % count($imagePool)] ?? null,
                    'status' => 'published',
                    'published_at' => now()->subDays(4 + $index)->setHour(16)->setMinute(0),
                ]
            );

            for ($i = 1; $i <= 4; $i++) {
                GalleryItem::query()->updateOrCreate(
                    [
                        'gallery_id' => $gallery->id,
                        'sort_order' => $i,
                    ],
                    [
                        'image_path' => $imagePool[($index + $i) % count($imagePool)] ?? ($imagePool[0] ?? 'dummy/icmi-cfd-2026.jpeg'),
                        'caption' => $title . ' - Dokumentasi sesi ' . $i,
                    ]
                );
            }
        }
    }

    /**
     * @param array<int, string> $imagePool
     */
    private function seedVideos(int $authorId, array $imagePool): void
    {
        $videos = [
            ['title' => 'Halalbihalal ICMI Kaltim: Rekonsiliasi untuk Kemajuan Bangsa', 'youtube_id' => 'dQw4w9WgXcQ'],
            ['title' => 'Dialog ICMI TV: Strategi SDM Unggul Kalimantan Timur', 'youtube_id' => 'aqz-KE-bpKQ'],
            ['title' => 'Program IMTAQ: Penguatan Etika Publik Cendekiawan Muslim', 'youtube_id' => '3JZ_D3ELwOQ'],
            ['title' => 'Tokoh Bicara: Kepemimpinan Berintegritas di Era Digital', 'youtube_id' => 'L_jWHffIx5E'],
            ['title' => 'Siaran Pers ICMI: Ekonomi Umat dan Kemandirian Daerah', 'youtube_id' => 'fJ9rUzIMcZQ'],
            ['title' => 'Workshop ICMI TV: Literasi AI untuk Komunitas Pendidikan', 'youtube_id' => '9bZkp7q19f0'],
            ['title' => 'Podcast Cendekia: Menata Masa Depan Pemuda Kaltim', 'youtube_id' => 'kJQP7kiw5Fk'],
            ['title' => 'ICMI Update: Program Sosial Kesehatan Berkelanjutan', 'youtube_id' => 'YQHsXMglC9A'],
        ];

        foreach ($videos as $index => $video) {
            $title = $video['title'];
            $youtubeId = $video['youtube_id'];
            Video::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'author_id' => $authorId,
                    'title' => $title,
                    'description' => 'Program ICMI TV: ' . $title . '. Tayangan ini menghadirkan perspektif tokoh, gagasan kebijakan, dan inspirasi kontribusi publik.',
                    'seo_title' => $title . ' | ICMI TV',
                    'seo_description' => 'Tonton program ICMI TV: ' . $title . ' dengan pembahasan aktual untuk kemajuan daerah.',
                    'youtube_url' => 'https://www.youtube.com/watch?v=' . $youtubeId,
                    'youtube_id' => $youtubeId,
                    'thumbnail' => $imagePool[($index + 2) % count($imagePool)] ?? null,
                    'status' => 'published',
                    'published_at' => now()->subDays($index)->setHour(19)->setMinute(0),
                ]
            );
        }
    }

    private function buildExcerpt(string $title): string
    {
        return Str::limit(
            $title . ' menjadi bagian dari komitmen ICMI Kaltim dalam memperkuat kontribusi pemikiran, aksi sosial, dan kolaborasi pembangunan daerah secara berkelanjutan.',
            280
        );
    }

    private function buildArticleHtml(string $title, string $context): string
    {
        $contextLine = match ($context) {
            'media' => 'Materi ini disusun sebagai rujukan resmi kanal informasi kelembagaan ICMI Kaltim.',
            'opinion' => 'Artikel ini merekam sudut pandang tokoh dan cendekiawan untuk memperkaya diskursus publik.',
            default => 'Naskah ini disusun berdasarkan agenda kerja organisasi dan kebutuhan aktual masyarakat.',
        };

        return '<p><strong>' . e($title) . '</strong> menjadi salah satu fokus program ICMI Kaltim untuk memperluas dampak kemanfaatan publik di Kalimantan Timur.</p>'
            . '<p>Melalui pendekatan kolaboratif antara akademisi, profesional, dan komunitas, ICMI Kaltim mendorong program yang terukur, inklusif, dan adaptif terhadap dinamika pembangunan daerah.</p>'
            . '<p>' . e($contextLine) . '</p>'
            . '<p>Ke depan, inisiatif ini akan terus diperkuat melalui kemitraan lintas sektor, penguatan literasi publik, dan inovasi kebijakan berbasis data.</p>';
    }
}
