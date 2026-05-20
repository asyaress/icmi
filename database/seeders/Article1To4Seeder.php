<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Article1To4Seeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('email', 'admin@gmail.com')->first()
            ?? User::query()->first();

        if (! $author) {
            return;
        }

        $kabarIcmi = Category::query()->updateOrCreate(
            ['slug' => 'kabar-icmi'],
            [
                'name' => 'Kabar ICMI',
                'description' => 'Publikasi kegiatan dan agenda strategis ICMI Kaltim.',
                'is_active' => true,
            ]
        );

        $opini = Category::query()->updateOrCreate(
            ['slug' => 'opini'],
            [
                'name' => 'Opini',
                'description' => 'Artikel pemikiran dan gagasan tokoh.',
                'is_active' => true,
            ]
        );

        $this->seedArticle1($author->id, $kabarIcmi->id);
        $this->seedArticle2($author->id, $kabarIcmi->id);
        $this->seedArticle3($author->id, $opini->id);
        $this->seedArticle4($author->id, $kabarIcmi->id);
    }

    private function copyFromPublic(string $sourceRelativePath, string $destRelativePath): ?string
    {
        $source = public_path($sourceRelativePath);
        $dest = storage_path('app/public/' . $destRelativePath);

        if (! File::exists($source)) {
            return null;
        }

        $destDir = dirname($dest);
        if (! File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        File::copy($source, $dest);

        return $destRelativePath;
    }

    private function seedArticle1(int $authorId, int $categoryId): void
    {
        $featuredRel = $this->copyFromPublic('artikel1/16587.jpg', 'posts/2026/05/icmi-konsolidasi-16587.jpg');
        $inlineRel = $this->copyFromPublic('artikel1/IMG_9113-2.jpg', 'posts/2026/05/icmi-konsolidasi-img-9113-2.jpg');

        $title = 'ICMI Kaltim Mantapkan Konsolidasi Jelang Pelantikan';
        $slug = Str::slug($title);

        $content = <<<HTML
<p><strong>Samarinda</strong> - Ikatan Cendekiawan Muslim se-Indonesia (ICMI) Organisasi Wilayah Kalimantan Timur menggelar <em>Silaturahim Pengurus dan Finalisasi Penyusunan Kepengurusan Baru</em> pada Rabu, 13 Mei 2026, di Samarinda Room, Hotel Puri Senyiur, Samarinda.</p>
<p>Kegiatan yang berlangsung pukul 09.00-12.00 WITA ini menghadirkan para cendekiawan, akademisi, profesional, dan tokoh masyarakat dari berbagai institusi di Kalimantan Timur. Acara ini menjadi momen strategis konsolidasi organisasi menjelang pelantikan resmi pengurus ICMI Orwil Kaltim oleh ICMI Pusat.</p>
<p><img src="/storage/{$inlineRel}" alt="Ketua Formatur ICMI Kaltim memberikan sambutan" style="max-width:100%;height:auto;border-radius:8px;"></p>
<h3>Penguatan Ukhuwah dan Sinergi Pengurus</h3>
<p>Ketua Panitia, Prof. Dr. Rahmawati, MM, menegaskan bahwa kegiatan ini dirancang untuk mempererat ukhuwah sekaligus menyempurnakan struktur kepengurusan baru.</p>
<p>"Seluruh unsur pengurus telah diundang, mulai dari Dewan Penasehat, Dewan Pakar, hingga Majelis Pengurus. Total ada 256 pengurus yang terlibat," ujarnya.</p>
<h3>Ketua Formatur: ICMI Harus Jadi Mitra Strategis Pembangunan</h3>
<p>Ketua Formatur ICMI Orwil Kaltim, Prof. Dr. Ir. H. Abdunnur, M.Si., IPU., ASEAN Eng., menegaskan bahwa ICMI harus tampil sebagai kekuatan intelektual yang mampu memberi kontribusi nyata bagi pembangunan daerah dan nasional.</p>
<p>"Kita berhikmat di ICMI ini tentu bagaimana mengedepankan kecendekiawanan yang mampu membangun sinergi, mendukung pembangunan daerah maupun nasional," tegasnya.</p>
<h3>Program Sosial ICMI Kaltim Terus Diperluas</h3>
<ul>
    <li><strong>Launching</strong> dan diskusi buku Sejarah Islam Samarinda.</li>
    <li>Cek kesehatan gratis dan skrining kesehatan mental rutin di Car Free Day GOR Segiri.</li>
    <li>Sunatan gratis bagi anak yatim dan kaum duafa.</li>
</ul>
<p><em>Sumber: Warta Kaltim (13 Mei 2026)</em></p>
HTML;

        Post::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'type' => Post::TYPE_NEWS,
                'title' => $title,
                'excerpt' => 'ICMI Kaltim menggelar silaturahim pengurus dan finalisasi kepengurusan baru di Samarinda sebagai langkah konsolidasi strategis menjelang pelantikan resmi oleh ICMI Pusat.',
                'content' => $content,
                'seo_title' => $title,
                'seo_description' => 'Silaturahim pengurus dan finalisasi kepengurusan ICMI Kaltim jelang pelantikan resmi.',
                'featured_image' => $featuredRel,
                'status' => 'published',
                'published_at' => '2026-05-13 09:00:00',
            ]
        );
    }

    private function seedArticle2(int $authorId, int $categoryId): void
    {
        $featuredRel = $this->copyFromPublic('artikel2/icmi-cfd-2026.jpeg', 'posts/2026/05/icmi-cfd-2026-icmi-cfd-2026.jpeg');
        $title = 'ICMI Kaltim Perluas Aksi Sosial Lewat Cek Kesehatan Gratis di CFD Samarinda';
        $slug = Str::slug($title);

        $content = <<<HTML
<p><strong>KORANKALTIM.COM, Samarinda</strong> - ICMI Kalimantan Timur (Kaltim) terus memperluas kontribusi sosial kepada masyarakat melalui program cek kesehatan gratis yang digelar rutin di kawasan Car Free Day (CFD) GOR Segiri Samarinda.</p>
<p>Kegiatan tersebut merupakan inisiasi ICMI Kaltim yang berkolaborasi bersama Poltekkes Kemenkes Kalimantan Timur sebagai tim medis pelaksana pemeriksaan kesehatan.</p>
<p>Program sosial ini menghadirkan berbagai layanan pemeriksaan kesehatan mulai dari pengecekan tekanan darah, gula darah, kolesterol, asam urat, hingga edukasi kesehatan bagi masyarakat umum.</p>
<p><img src="/storage/{$featuredRel}" alt="Kegiatan cek kesehatan gratis ICMI Kaltim di CFD Samarinda" style="max-width:100%;height:auto;border-radius:8px;"></p>
<p>"Harapannya masyarakat bisa lebih sadar untuk melakukan screening kesehatan sebelum jatuh sakit. Jadi kondisi-kondisi yang berisiko bisa diketahui lebih awal dan dicegah agar tidak menjadi lebih parah," ujar Dwi Hendriani.</p>
<p>"Alhamdulillah animo masyarakat sangat baik. Mudah-mudahan ke depan kuota pemeriksaan bisa ditambah sehingga semakin banyak warga yang mendapat layanan kesehatan gratis," katanya.</p>
<p><em>Penulis: M Rafik | Editor: Erwin | Sumber: Korankaltim.com (Adv), Minggu 10 Mei 2026</em></p>
HTML;

        Post::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'type' => Post::TYPE_NEWS,
                'title' => $title,
                'excerpt' => 'ICMI Kaltim bersama Poltekkes Kemenkes Kaltim memperluas aksi sosial melalui layanan cek kesehatan gratis rutin di CFD Samarinda.',
                'content' => $content,
                'seo_title' => $title,
                'seo_description' => 'Program cek kesehatan gratis ICMI Kaltim di CFD Samarinda bersama Poltekkes Kemenkes Kaltim.',
                'featured_image' => $featuredRel,
                'status' => 'published',
                'published_at' => '2026-05-10 09:00:00',
            ]
        );
    }

    private function seedArticle3(int $authorId, int $categoryId): void
    {
        $featuredRel = $this->copyFromPublic('artikel3/musthafa-vwlZ3.webp', 'posts/2026/05/icmi-ham-ekologis-musthafa-vwlz3.webp');
        $title = 'Menata Peran ICMI Kaltim Berbasis HAM dan Keadilan Ekologis';
        $slug = Str::slug($title);

        $content = <<<HTML
<p><strong>Oleh: Musthafa</strong><br>Kepala Pusat Penelitian Hak Asasi Manusia dan Multikulturalisme Tropis (PusHAM-MT) Universitas Mulawarman<br>Anggota ICMI Orwil Kaltim</p>
<p>Pertemuan Silaturahim Pengurus dan Penyusunan Akhir Calon Pengurus ICMI Orwil Kaltim pada 13 Mei 2026 bukan sekadar agenda organisasi, melainkan momentum penting untuk menata arah pembangunan Kalimantan Timur berbasis kemanusiaan.</p>
<p>Di tengah geliat investasi, pertambangan, dan pembangunan Ibu Kota Nusantara, Kalimantan Timur menghadapi persoalan hak hidup, hak atas lingkungan yang sehat, hak masyarakat adat, dan perlindungan warga sipil.</p>
<p><img src="/storage/{$featuredRel}" alt="Musthafa - Penulis Opini" style="max-width:100%;height:auto;border-radius:8px;"></p>
<h3>Pembangunan Berbasis HAM dan Keadilan Ekologis</h3>
<p>ICMI Kaltim perlu mendorong paradigma pembangunan berbasis HAM dan keadilan ekologis, menempatkan manusia sebagai pusat pembangunan, bukan sekadar alat pertumbuhan ekonomi.</p>
<ul>
  <li>Advokasi kebijakan publik berbasis riset.</li>
  <li>Pendidikan publik terkait HAM dan lingkungan hidup.</li>
  <li>Perlindungan kelompok rentan dan masyarakat terdampak konflik sumber daya.</li>
</ul>
<p><em>Sumber: Prokal.co | Redaksi Prokal | Jumat, 15 Mei 2026 | Editor: Indra Zakaria</em></p>
HTML;

        Post::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'type' => Post::TYPE_OPINION,
                'title' => $title,
                'excerpt' => 'Tulisan Musthafa mengajak ICMI Kaltim memperkuat peran strategis dalam mendorong pembangunan daerah berbasis HAM dan keadilan ekologis.',
                'content' => $content,
                'seo_title' => $title,
                'seo_description' => 'Opini Musthafa tentang pentingnya peran ICMI Kaltim dalam pembangunan berbasis HAM dan keadilan ekologis.',
                'featured_image' => $featuredRel,
                'status' => 'published',
                'published_at' => '2026-05-15 23:06:00',
            ]
        );
    }

    private function seedArticle4(int $authorId, int $categoryId): void
    {
        $featuredRel = $this->copyFromPublic(
            'artikel4/492c7416e5298f14ea6a71a4d8270d1b_26021207433945403.jpeg',
            'posts/2026/02/icmi-muswil-abdunur-inline-492c7416e5298f14ea6a71a4d8270d1b-26021207433945403.jpeg'
        );

        $title = 'Prof Abdunur Pimpin ICMI Kaltim, Syahrie Jaang Harapkan Perkuat Peran Cendekiawan untuk Pembangunan Daerah';
        $slug = Str::slug($title);

        $content = <<<HTML
<p><strong>KORANKALTIM.COM, Samarinda</strong> - ICMI Wilayah Kaltim menggelar Musyawarah Wilayah (Muswil) II untuk menentukan arah organisasi periode 2026-2031 di Mesra Internasional Hotel Samarinda.</p>
<p>Muswil menyepakati Prof. Abdunur, Rektor Universitas Mulawarman, sebagai Ketua ICMI Kaltim periode mendatang.</p>
<p><img src="/storage/{$featuredRel}" alt="Ketua Demisioner ICMI Kaltim Syaharie Jaang dalam Muswil" style="max-width:100%;height:auto;border-radius:8px;"></p>
<p>Ketua Demisioner ICMI Kaltim, Syaharie Jaang, menyebut kepemimpinan baru diharapkan mampu mengonsolidasikan para cendekiawan dari berbagai perguruan tinggi untuk memberi masukan konstruktif kepada pemerintah.</p>
<h3>Perluasan Jejaring Kabupaten/Kota</h3>
<p>Di bawah kepemimpinan baru, ICMI Kaltim diharapkan memperluas jejaring organisasi hingga seluruh kabupaten/kota di Kalimantan Timur.</p>
<p><em>Penulis: Rahmat Surya | Editor: Aspian Nur | Sumber: Korankaltim.com, Kamis 12 Februari 2026</em></p>
HTML;

        Post::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'type' => Post::TYPE_NEWS,
                'title' => $title,
                'excerpt' => 'Muswil II ICMI Kaltim menetapkan Prof. Abdunur sebagai ketua periode 2026-2031 dengan harapan penguatan peran cendekiawan bagi pembangunan daerah.',
                'content' => $content,
                'seo_title' => $title,
                'seo_description' => 'Prof. Abdunur ditetapkan memimpin ICMI Kaltim pada Muswil II.',
                'featured_image' => $featuredRel,
                'status' => 'published',
                'published_at' => '2026-02-12 09:00:00',
            ]
        );
    }
}

