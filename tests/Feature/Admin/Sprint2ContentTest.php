<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\Admin\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Sprint2ContentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $role = Role::query()->where('slug', 'super-admin')->firstOrFail();

        $this->admin = User::query()->create([
            'name' => 'Admin Content',
            'email' => 'content-admin@icmi.test',
            'password' => 'password123',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_category_and_tag(): void
    {
        $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Kegiatan',
            'slug' => '',
            'description' => 'Kategori kegiatan',
            'is_active' => '1',
        ])->assertRedirect(route('admin.categories.index'));

        $this->actingAs($this->admin)->post(route('admin.tags.store'), [
            'name' => 'Seminar',
            'slug' => '',
        ])->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Kegiatan', 'slug' => 'kegiatan']);
        $this->assertDatabaseHas('tags', ['name' => 'Seminar', 'slug' => 'seminar']);
    }

    public function test_admin_can_create_published_post_with_featured_image(): void
    {
        Storage::fake('public');

        $category = Category::query()->create([
            'name' => 'Berita Utama',
            'slug' => 'berita-utama',
            'is_active' => true,
        ]);
        $tag = Tag::query()->create(['name' => 'ICMI', 'slug' => 'icmi']);

        $response = $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Peluncuran Program Baru',
            'slug' => '',
            'category_id' => $category->id,
            'excerpt' => 'Ringkasan berita.',
            'content' => 'Konten lengkap berita.',
            'status' => 'published',
            'tag_ids' => [$tag->id],
            'featured_image' => UploadedFile::fake()->image('featured.jpg', 1200, 800),
        ]);

        $response->assertRedirect(route('admin.posts.index'));

        $post = Post::query()->firstOrFail();
        $this->assertSame('peluncuran-program-baru', $post->slug);
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertNotNull($post->featured_image);
        Storage::disk('public')->assertExists($post->featured_image);
        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post->id,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_only_published_posts_are_visible_on_public_berita_page(): void
    {
        $category = Category::query()->create([
            'name' => 'Berita',
            'slug' => 'berita',
            'is_active' => true,
        ]);

        Post::query()->create([
            'category_id' => $category->id,
            'author_id' => $this->admin->id,
            'title' => 'Draft Internal',
            'slug' => 'draft-internal',
            'content' => 'Draft.',
            'status' => 'draft',
        ]);

        Post::query()->create([
            'category_id' => $category->id,
            'author_id' => $this->admin->id,
            'title' => 'Berita Dipublish',
            'slug' => 'berita-dipublish',
            'content' => 'Published.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('berita'))
            ->assertOk()
            ->assertSee('Berita Dipublish')
            ->assertDontSee('Draft Internal');

        $this->get(route('berita.show', 'berita-dipublish'))->assertOk();
        $this->get(route('berita.show', 'draft-internal'))->assertNotFound();
    }
}

