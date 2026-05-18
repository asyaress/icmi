<?php

namespace Database\Seeders;

use App\Models\ProfilePage;
use Illuminate\Database\Seeder;

class ProfilePageSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $pages = [
            [
                'title' => 'Sejarah',
                'menu_label' => 'SEJARAH',
                'slug' => 'sejarah',
                'menu_order' => 1,
                'content' => <<<'HTML'
<h3 style="text-align:center;">SEJARAH ICMI KALTIM</h3>
<p>Ikatan Cendekiawan Muslim Indonesia (ICMI) Kalimantan Timur hadir sebagai ruang sinergi para cendekiawan Muslim dalam menguatkan pemikiran, kontribusi sosial, dan pembangunan daerah. Perjalanan ICMI Kaltim tumbuh dari semangat kolaborasi lintas profesi, lintas generasi, serta lintas wilayah di Kalimantan Timur.</p>
<p>Sejak awal pembentukannya, ICMI Kaltim menempatkan nilai keilmuan, akhlak, dan kemanfaatan publik sebagai fondasi gerakan. Berbagai forum diskusi, advokasi kebijakan, penguatan SDM, dan aksi sosial menjadi bagian dari perjalanan organisasi.</p>
<p>Dengan dukungan tokoh, akademisi, profesional, dan komunitas, ICMI Kaltim terus berkomitmen memperkuat peran cendekiawan Muslim untuk kemajuan daerah dan kesejahteraan masyarakat.</p>
HTML,
            ],
            [
                'title' => 'Visi & Misi',
                'menu_label' => 'VISI & MISI',
                'slug' => 'visi-misi',
                'menu_order' => 2,
                'content' => <<<'HTML'
<h3 style="text-align:center;">VISI ICMI KALTIM</h3>
<p>Menjadi pusat gerakan cendekiawan Muslim yang berintegritas, progresif, kolaboratif, dan berdampak dalam pembangunan Kalimantan Timur.</p>
<h3 style="text-align:center;">MISI ICMI KALTIM</h3>
<ol>
    <li>Mengembangkan tradisi intelektual yang berlandaskan nilai keislaman dan kebangsaan.</li>
    <li>Membangun kolaborasi strategis dengan pemerintah, kampus, dunia usaha, dan masyarakat sipil.</li>
    <li>Mendorong solusi nyata untuk isu pendidikan, ekonomi, sosial, dan lingkungan daerah.</li>
    <li>Memperkuat kaderisasi cendekiawan Muslim yang adaptif terhadap perubahan zaman.</li>
</ol>
HTML,
            ],
            [
                'title' => 'Susunan Pengurus',
                'menu_label' => 'SUSUNAN PENGURUS',
                'slug' => 'susunan-pengurus',
                'menu_order' => 3,
                'content' => <<<'HTML'
<h3 style="text-align:center;">SUSUNAN PENGURUS ICMI KALTIM</h3>
<p>Berikut struktur kepengurusan ICMI Kaltim periode berjalan. Data nama dan jabatan dapat diperbarui melalui panel admin sesuai keputusan organisasi terbaru.</p>
<h4>Pengurus Harian</h4>
<ul>
    <li>Ketua Umum</li>
    <li>Sekretaris Umum</li>
    <li>Bendahara Umum</li>
</ul>
<h4>Bidang-Bidang</h4>
<ul>
    <li>Bidang Organisasi dan Kaderisasi</li>
    <li>Bidang Kajian Strategis dan Kebijakan Publik</li>
    <li>Bidang Pendidikan, Sains, dan Teknologi</li>
    <li>Bidang Ekonomi Umat dan Kewirausahaan</li>
</ul>
HTML,
            ],
            [
                'title' => 'Jaringan ICMI',
                'menu_label' => 'JARINGAN ICMI',
                'slug' => 'jaringan-icmi',
                'menu_order' => 4,
                'content' => <<<'HTML'
<h3 style="text-align:center;">JARINGAN ICMI</h3>
<p>ICMI Kaltim membangun jaringan kerja kolaboratif dengan berbagai pemangku kepentingan untuk memperluas dampak gerakan cendekiawan Muslim di daerah.</p>
<p>Jaringan ini mencakup kolaborasi dengan pemerintah daerah, perguruan tinggi, organisasi profesi, komunitas kepemudaan, pelaku UMKM, hingga lembaga filantropi.</p>
<p>Melalui jaringan yang kuat, program ICMI Kaltim dirancang agar lebih terukur, berkelanjutan, dan langsung menyentuh kebutuhan masyarakat.</p>
HTML,
            ],
            [
                'title' => 'Anggaran Dasar',
                'menu_label' => 'ANGGARAN DASAR',
                'slug' => 'anggaran-dasar',
                'menu_order' => 5,
                'content' => <<<'HTML'
<h3 style="text-align:center;">ANGGARAN DASAR</h3>
<h4 style="text-align:center;">IKATAN CENDEKIAWAN MUSLIM INDONESIA</h4>
<h4 style="text-align:center;">PERIODE 2021 - 2026</h4>
<h4 style="text-align:center;">MUKADIMAH</h4>
<p style="text-align:center;"><em>Bismillahirrahmanirrahim</em></p>
<p>Sesungguhnya, hikmah adalah nikmat Allah subhanahu wata'ala yang tertinggi dan termulia yang dikaruniakan kepada hamba-Nya yang beriman, bertaqwa, berilmu, dan beramal. Oleh karena itu penerima hikmah wajib bersyukur dengan memanfaatkannya sebagai wujud pengabdian kepada Allah subhanahu wata'ala melalui perjuangan membangun umat, masyarakat, bangsa, negara, dan dunia.</p>
<p>Cendekiawan muslim dalam kedudukannya sebagai abdi Allah subhanahu wata'ala, selaku warga negara Republik Indonesia yang sadar akan besarnya tantangan perubahan paradigmatik yang sedang dan akan dihadapi oleh bangsa perlu mengembangkan peluang dan merumuskan pemikiran dan konsep strategis, sekaligus mengupayakan pemecahan konkrit permasalahan strategis lokal, regional, nasional, dan global menuju rahmatan lil'alamin.</p>
<h4 style="text-align:center;">BAB I</h4>
<h4 style="text-align:center;">NAMA, WAKTU DAN KEDUDUKAN</h4>
<p>Isi pasal-pasal dapat dilengkapi dan diperbarui sesuai dokumen resmi organisasi.</p>
HTML,
            ],
            [
                'title' => 'ART',
                'menu_label' => 'ART',
                'slug' => 'art',
                'menu_order' => 6,
                'content' => <<<'HTML'
<h3 style="text-align:center;">ANGGARAN RUMAH TANGGA (ART)</h3>
<p>Dokumen ART ICMI Kaltim memuat ketentuan pelaksanaan organisasi, mekanisme kerja kepengurusan, tata kelola program, serta ketentuan administrasi organisasi.</p>
<p>Seluruh ketentuan dalam ART menjadi pedoman operasional untuk menjaga efektivitas, akuntabilitas, dan kesinambungan gerakan organisasi di tingkat wilayah.</p>
<p>Konten ART dapat diselaraskan secara berkala sesuai hasil musyawarah dan keputusan organisasi.</p>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            ProfilePage::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'menu_label' => $page['menu_label'],
                    'menu_order' => $page['menu_order'],
                    'content' => $page['content'],
                    'status' => 'published',
                    'published_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
