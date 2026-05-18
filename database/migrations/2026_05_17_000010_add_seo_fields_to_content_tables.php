<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('seo_title')->nullable()->after('content');
            $table->text('seo_description')->nullable()->after('seo_title');
        });

        Schema::table('galleries', function (Blueprint $table): void {
            $table->string('seo_title')->nullable()->after('description');
            $table->text('seo_description')->nullable()->after('seo_title');
        });

        Schema::table('videos', function (Blueprint $table): void {
            $table->string('seo_title')->nullable()->after('description');
            $table->text('seo_description')->nullable()->after('seo_title');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'seo_description']);
        });

        Schema::table('galleries', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'seo_description']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'seo_description']);
        });
    }
};
