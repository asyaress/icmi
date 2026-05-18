<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Admin\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint3OpinionMediaTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $role = Role::query()->where('slug', 'super-admin')->firstOrFail();

        $this->admin = User::query()->create([
            'name' => 'Admin Sprint3',
            'email' => 'admin-s3@icmi.test',
            'password' => 'password123',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_opinion_and_media_info_posts(): void
    {
        $category = Category::query()->create([
            'name' => 'Publikasi',
            'slug' => 'publikasi',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)->post(route('admin.opinions.store'), [
            'title' => 'Opini Cendekia',
            'category_id' => $category->id,
            'excerpt' => 'Excerpt opini',
            'content' => 'Konten opini',
            'status' => 'published',
        ])->assertRedirect(route('admin.opinions.index'));

        $this->actingAs($this->admin)->post(route('admin.media-info.store'), [
            'title' => 'Siaran Pers ICMI',
            'category_id' => $category->id,
            'excerpt' => 'Excerpt media',
            'content' => 'Konten media info',
            'status' => 'published',
        ])->assertRedirect(route('admin.media-info.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Opini Cendekia',
            'type' => Post::TYPE_OPINION,
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Siaran Pers ICMI',
            'type' => Post::TYPE_MEDIA_INFO,
        ]);
    }

    public function test_public_listing_and_filter_work_for_opinion_and_media_info(): void
    {
        $categoryA = Category::query()->create([
            'name' => 'A',
            'slug' => 'a',
            'is_active' => true,
        ]);
        $categoryB = Category::query()->create([
            'name' => 'B',
            'slug' => 'b',
            'is_active' => true,
        ]);

        Post::query()->create([
            'category_id' => $categoryA->id,
            'author_id' => $this->admin->id,
            'type' => Post::TYPE_OPINION,
            'title' => 'Opini A',
            'slug' => 'opini-a',
            'content' => 'Opini A',
            'status' => 'published',
            'published_at' => now(),
        ]);
        Post::query()->create([
            'category_id' => $categoryB->id,
            'author_id' => $this->admin->id,
            'type' => Post::TYPE_OPINION,
            'title' => 'Opini B',
            'slug' => 'opini-b',
            'content' => 'Opini B',
            'status' => 'published',
            'published_at' => now(),
        ]);
        Post::query()->create([
            'category_id' => $categoryA->id,
            'author_id' => $this->admin->id,
            'type' => Post::TYPE_MEDIA_INFO,
            'title' => 'Media A',
            'slug' => 'media-a',
            'content' => 'Media A',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('opini-tokoh', ['category' => 'a']))
            ->assertOk()
            ->assertSee('Opini A')
            ->assertDontSee('Opini B');

        $this->get(route('info-media', ['q' => 'Media']))
            ->assertOk()
            ->assertSee('Media A');

        $this->get(route('opini-tokoh.show', 'opini-a'))->assertOk();
        $this->get(route('info-media.show', 'media-a'))->assertOk();
    }
}

