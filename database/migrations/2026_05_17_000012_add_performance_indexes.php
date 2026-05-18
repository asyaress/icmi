<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->index(['type', 'status', 'published_at'], 'posts_type_status_published_idx');
            $table->index(['category_id', 'status', 'published_at'], 'posts_category_status_published_idx');
        });

        Schema::table('galleries', function (Blueprint $table): void {
            $table->index(['status', 'published_at'], 'galleries_status_published_idx');
        });

        Schema::table('gallery_items', function (Blueprint $table): void {
            $table->index(['gallery_id', 'sort_order'], 'gallery_items_gallery_sort_idx');
        });

        Schema::table('videos', function (Blueprint $table): void {
            $table->index(['status', 'published_at'], 'videos_status_published_idx');
        });

        Schema::table('settings', function (Blueprint $table): void {
            $table->index(['group', 'key'], 'settings_group_key_idx');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropIndex('settings_group_key_idx');
        });

        Schema::table('videos', function (Blueprint $table): void {
            $table->dropIndex('videos_status_published_idx');
        });

        Schema::table('gallery_items', function (Blueprint $table): void {
            $table->dropIndex('gallery_items_gallery_sort_idx');
        });

        Schema::table('galleries', function (Blueprint $table): void {
            $table->dropIndex('galleries_status_published_idx');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropIndex('posts_type_status_published_idx');
            $table->dropIndex('posts_category_status_published_idx');
        });
    }
};
