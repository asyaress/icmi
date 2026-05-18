<?php

namespace Tests\Feature\Admin;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Role;
use App\Models\User;
use App\Models\Video;
use Database\Seeders\Admin\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Sprint4GalleryVideoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $role = Role::query()->where('slug', 'super-admin')->firstOrFail();

        $this->admin = User::query()->create([
            'name' => 'Admin Sprint4',
            'email' => 'admin-s4@icmi.test',
            'password' => 'password123',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_gallery_with_multiple_images_and_video(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('admin.galleries.store'), [
            'title' => 'Galeri Musda ICMI',
            'description' => 'Dokumentasi kegiatan musda.',
            'status' => 'published',
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
            'images' => [
                UploadedFile::fake()->image('img1.jpg'),
                UploadedFile::fake()->image('img2.jpg'),
            ],
            'captions' => ['Pembukaan acara', 'Sesi diskusi'],
        ])->assertRedirect(route('admin.galleries.index'));

        $gallery = Gallery::query()->firstOrFail();
        $this->assertSame('galeri-musda-icmi', $gallery->slug);
        $this->assertSame('published', $gallery->status);
        $this->assertNotNull($gallery->published_at);
        $this->assertNotNull($gallery->cover_image);
        Storage::disk('public')->assertExists($gallery->cover_image);
        $this->assertSame(2, GalleryItem::query()->where('gallery_id', $gallery->id)->count());

        $this->actingAs($this->admin)->post(route('admin.videos.store'), [
            'title' => 'Talkshow ICMI TV',
            'description' => 'Episode perdana.',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'status' => 'published',
            'thumbnail' => UploadedFile::fake()->image('thumb.jpg'),
        ])->assertRedirect(route('admin.videos.index'));

        $video = Video::query()->firstOrFail();
        $this->assertSame('talkshow-icmi-tv', $video->slug);
        $this->assertSame('dQw4w9WgXcQ', $video->youtube_id);
        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->embed_url);
    }

    public function test_public_gallery_and_video_pages_show_only_published_content(): void
    {
        $publishedGallery = Gallery::query()->create([
            'author_id' => $this->admin->id,
            'title' => 'Galeri Publish',
            'slug' => 'galeri-publish',
            'status' => 'published',
            'published_at' => now(),
        ]);

        GalleryItem::query()->create([
            'gallery_id' => $publishedGallery->id,
            'image_path' => 'galleries/items/demo.jpg',
            'caption' => 'Demo foto',
            'sort_order' => 1,
        ]);

        Gallery::query()->create([
            'author_id' => $this->admin->id,
            'title' => 'Galeri Draft',
            'slug' => 'galeri-draft',
            'status' => 'draft',
        ]);

        Video::query()->create([
            'author_id' => $this->admin->id,
            'title' => 'Video Publish',
            'slug' => 'video-publish',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Video::query()->create([
            'author_id' => $this->admin->id,
            'title' => 'Video Draft',
            'slug' => 'video-draft',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'status' => 'draft',
        ]);

        $this->get(route('galeri'))
            ->assertOk()
            ->assertSee('Galeri Publish')
            ->assertDontSee('Galeri Draft');

        $this->get(route('galeri.show', 'galeri-publish'))->assertOk()->assertSee('Demo foto');
        $this->get(route('galeri.show', 'galeri-draft'))->assertNotFound();

        $this->get(route('icmi-tv'))
            ->assertOk()
            ->assertSee('Video Publish')
            ->assertDontSee('Video Draft');

        $this->get(route('icmi-tv.show', 'video-publish'))->assertOk()->assertSee('youtube.com/embed/dQw4w9WgXcQ');
        $this->get(route('icmi-tv.show', 'video-draft'))->assertNotFound();
    }
}
