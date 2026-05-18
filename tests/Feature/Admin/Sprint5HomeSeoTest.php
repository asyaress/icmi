<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Models\Video;
use Database\Seeders\Admin\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint5HomeSeoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $role = Role::query()->where('slug', 'super-admin')->firstOrFail();

        $this->admin = User::query()->create([
            'name' => 'Admin Sprint5',
            'email' => 'admin-s5@icmi.test',
            'password' => 'password123',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_home_settings(): void
    {
        $this->actingAs($this->admin)
            ->put(route('admin.settings.home.update'), [
                'site_name' => 'ICMI Kaltim Test',
                'site_tagline' => 'Tagline Test',
                'meta_default_description' => 'Deskripsi default test',
                'home_show_hero' => '1',
                'home_show_trending' => '0',
                'home_show_featured' => '1',
                'home_show_video' => '1',
                'home_show_trending_today' => '1',
                'home_show_main_posts' => '1',
                'home_hero_limit' => 2,
                'home_trending_limit' => 7,
                'home_featured_limit' => 4,
                'home_video_limit' => 5,
                'home_trending_today_limit' => 4,
                'home_main_posts_limit' => 6,
            ])
            ->assertRedirect(route('admin.settings.home.edit'));

        $this->assertSame('ICMI Kaltim Test', Setting::get('site_name'));
        $this->assertSame('0', Setting::get('home_show_trending'));
    }

    public function test_homepage_and_meta_are_dynamic_and_sitemap_is_available(): void
    {
        $category = Category::query()->create([
            'name' => 'Berita',
            'slug' => 'berita',
            'is_active' => true,
        ]);

        $post = Post::query()->create([
            'category_id' => $category->id,
            'author_id' => $this->admin->id,
            'type' => Post::TYPE_NEWS,
            'title' => 'Judul Berita SEO',
            'slug' => 'judul-berita-seo',
            'excerpt' => 'Ringkasan berita untuk SEO.',
            'content' => 'Konten berita.',
            'seo_title' => 'Meta Title Berita SEO',
            'seo_description' => 'Meta Description Berita SEO',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Video::query()->create([
            'author_id' => $this->admin->id,
            'title' => 'Video SEO',
            'slug' => 'video-seo',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Setting::set('home_show_trending', '0', 'home');
        Setting::set('site_name', 'ICMI Kaltim Dinamis', 'home');
        Setting::set('site_tagline', 'Portal Publikasi', 'home');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('ICMI Kaltim Dinamis - Portal Publikasi')
            ->assertDontSee('binduz-er-trending-area');

        $this->get(route('berita.show', $post->slug))
            ->assertOk()
            ->assertSee('Meta Title Berita SEO')
            ->assertSee('Meta Description Berita SEO');

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('berita.show', $post->slug))
            ->assertSee(route('icmi-tv.show', 'video-seo'));
    }
}
