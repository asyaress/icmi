<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('menu_label')->nullable();
            $table->string('slug')->unique();
            $table->unsignedInteger('menu_order')->default(0);
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
            $table->index('menu_order');
        });

        $now = now();
        $defaultContent = <<<'HTML'
<p>Silakan isi konten profil organisasi melalui Admin. Anda dapat menulis teks panjang, menambahkan heading, daftar, dan lampiran dokumen sesuai kebutuhan halaman.</p>
<p>Konten ini adalah placeholder awal agar halaman langsung terisi. Silakan ganti dengan materi resmi ICMI Kaltim.</p>
HTML;

        DB::table('profile_pages')->insert([
            [
                'title' => 'Sejarah',
                'menu_label' => 'SEJARAH',
                'slug' => 'sejarah',
                'menu_order' => 1,
                'content' => $defaultContent,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Visi & Misi',
                'menu_label' => 'VISI & MISI',
                'slug' => 'visi-misi',
                'menu_order' => 2,
                'content' => $defaultContent,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Susunan Pengurus',
                'menu_label' => 'SUSUNAN PENGURUS',
                'slug' => 'susunan-pengurus',
                'menu_order' => 3,
                'content' => $defaultContent,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Jaringan ICMI',
                'menu_label' => 'JARINGAN ICMI',
                'slug' => 'jaringan-icmi',
                'menu_order' => 4,
                'content' => $defaultContent,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Anggaran Dasar',
                'menu_label' => 'ANGGARAN DASAR',
                'slug' => 'anggaran-dasar',
                'menu_order' => 5,
                'content' => $defaultContent,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'ART',
                'menu_label' => 'ART',
                'slug' => 'art',
                'menu_order' => 6,
                'content' => $defaultContent,
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_pages');
    }
};
