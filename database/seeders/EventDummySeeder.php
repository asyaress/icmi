<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EventDummySeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::query()->where('email', 'admin@gmail.com')->value('id')
            ?? User::query()->value('id');

        if (! $authorId) {
            return;
        }

        $sourceImage = base_path('public/WhatsApp Image 2026-05-17 at 21.49.07.jpeg');
        $storageImage = 'dummy/icmi-cfd-2026.jpeg';

        if (is_file($sourceImage)) {
            Storage::disk('public')->put($storageImage, file_get_contents($sourceImage));
        }

        $kabarIcmiCategoryId = Category::query()->where('slug', 'kabar-icmi')->value('id');
        $siaranPersCategoryId = Category::query()->where('slug', 'siaran-pers')->value('id');
        $tokohCategoryId = Category::query()->where('slug', 'tokoh')->value('id');

        $now = now();

        if ($kabarIcmiCategoryId) {
            Post::query()->updateOrCreate(
                ['slug' => 'pemeriksaan-kesehatan-gratis-cfd-2026'],
                [
                    'category_id' => $kabarIcmiCategoryId,
                    'author_id' => $authorId,
                    'type' => Post::TYPE_MEDIA_INFO,
                    'title' => 'Pemeriksaan Kesehatan Gratis ICMI Kaltim di CFD 2026',
                    'excerpt' => 'ICMI Kaltim menggelar layanan cek kesehatan gratis dan edukasi kesehatan untuk masyarakat di area CFD Jalan Kusuma Bangsa.',
                    'content' => "<p>ICMI Kaltim berkolaborasi dengan mitra strategis menghadirkan layanan pemeriksaan kesehatan gratis di area Car Free Day (CFD). Kegiatan ini meliputi cek tekanan darah, kolesterol, asam urat, dan gula darah.</p><p>Program ini ditujukan untuk memperluas akses layanan kesehatan dasar sekaligus meningkatkan literasi kesehatan masyarakat melalui edukasi langsung di lapangan.</p><p>Ke depan, kegiatan serupa direncanakan hadir berkala dengan skala layanan yang lebih luas.</p>",
                    'featured_image' => $storageImage,
                    'status' => 'published',
                    'published_at' => $now,
                ]
            );
        }

        if ($siaranPersCategoryId) {
            Post::query()->updateOrCreate(
                ['slug' => 'siaran-pers-kolaborasi-kesehatan-cfd-2026'],
                [
                    'category_id' => $siaranPersCategoryId,
                    'author_id' => $authorId,
                    'type' => Post::TYPE_MEDIA_INFO,
                    'title' => 'Siaran Pers: Kolaborasi ICMI Kaltim untuk Edukasi Kesehatan CFD 2026',
                    'excerpt' => 'Siaran pers resmi kegiatan pemeriksaan kesehatan gratis ICMI Kaltim bersama mitra di CFD.',
                    'content' => "<p>ICMI Kaltim menyampaikan siaran pers terkait pelaksanaan kegiatan pemeriksaan kesehatan gratis yang digelar dalam momentum CFD. Kegiatan ini menjadi bagian dari komitmen organisasi dalam pelayanan sosial berbasis kebutuhan masyarakat.</p><p>Pelaksanaan kegiatan mendapatkan antusiasme tinggi dari warga dan menjadi model kolaborasi positif antara organisasi masyarakat, institusi kesehatan, dan mitra komunitas.</p>",
                    'featured_image' => $storageImage,
                    'status' => 'published',
                    'published_at' => $now,
                ]
            );
        }

        if ($tokohCategoryId) {
            Post::query()->updateOrCreate(
                ['slug' => 'tokoh-icmi-dukung-edukasi-kesehatan-masyarakat'],
                [
                    'category_id' => $tokohCategoryId,
                    'author_id' => $authorId,
                    'type' => Post::TYPE_OPINION,
                    'title' => 'Tokoh ICMI: Edukasi Kesehatan adalah Investasi Sosial Jangka Panjang',
                    'excerpt' => 'Pandangan tokoh ICMI tentang pentingnya kolaborasi lintas sektor dalam edukasi kesehatan masyarakat.',
                    'content' => "<p>Menurut tokoh ICMI, edukasi kesehatan masyarakat harus dilihat sebagai investasi sosial jangka panjang. Upaya promotif dan preventif menjadi kunci agar masyarakat lebih sadar terhadap deteksi dini risiko kesehatan.</p><p>Program layanan langsung seperti pemeriksaan gratis di ruang publik juga dinilai efektif menjangkau kelompok masyarakat yang belum rutin melakukan pengecekan kesehatan.</p>",
                    'featured_image' => $storageImage,
                    'status' => 'published',
                    'published_at' => $now,
                ]
            );
        }

        $gallery = Gallery::query()->updateOrCreate(
            ['slug' => 'dokumentasi-cfd-pemeriksaan-kesehatan-2026'],
            [
                'author_id' => $authorId,
                'title' => 'Dokumentasi CFD: Pemeriksaan Kesehatan Gratis 2026',
                'description' => 'Dokumentasi kegiatan layanan kesehatan dan edukasi masyarakat oleh ICMI Kaltim di area CFD.',
                'cover_image' => $storageImage,
                'status' => 'published',
                'published_at' => $now,
            ]
        );

        GalleryItem::query()->updateOrCreate(
            [
                'gallery_id' => $gallery->id,
                'image_path' => $storageImage,
            ],
            [
                'caption' => 'Banner kegiatan Pemeriksaan Kesehatan Gratis ICMI Kaltim di CFD.',
                'sort_order' => 1,
            ]
        );
    }
}
