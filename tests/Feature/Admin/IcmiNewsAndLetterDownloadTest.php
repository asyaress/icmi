<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Admin\RolePermissionSeeder;
use Database\Seeders\IcmiNewsCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IcmiNewsAndLetterDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([RolePermissionSeeder::class, IcmiNewsCategorySeeder::class]);

        $this->admin = User::query()->create([
            'name' => 'Admin Revisi',
            'email' => 'admin-revisi@icmi.test',
            'password' => 'password123',
            'role_id' => Role::query()->where('slug', 'super-admin')->firstOrFail()->id,
            'is_active' => true,
        ]);
    }

    public function test_news_menu_and_admin_form_use_icmi_categories(): void
    {
        $pusat = Category::query()->where('slug', Category::ICMI_PUSAT_SLUG)->firstOrFail();
        $daerah = Category::query()->where('slug', Category::ICMI_DAERAH_SLUG)->firstOrFail();

        Post::query()->create([
            'category_id' => $pusat->id,
            'author_id' => $this->admin->id,
            'type' => Post::TYPE_NEWS,
            'title' => 'Kabar dari ICMI Pusat',
            'slug' => 'kabar-dari-icmi-pusat',
            'content' => 'Isi berita pusat.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Post::query()->create([
            'category_id' => $daerah->id,
            'author_id' => $this->admin->id,
            'type' => Post::TYPE_NEWS,
            'title' => 'Kabar dari ICMI Daerah',
            'slug' => 'kabar-dari-icmi-daerah',
            'content' => 'Isi berita daerah.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('berita', ['category' => Category::ICMI_PUSAT_SLUG]))
            ->assertOk()
            ->assertSee('Kabar dari ICMI Pusat')
            ->assertDontSee('Kabar dari ICMI Daerah');

        $this->actingAs($this->admin)->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('ICMI Daerah')
            ->assertSee('ICMI Pusat');
    }

    public function test_admin_can_publish_a_letter_for_public_download(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('admin.downloads.store'), [
            'title' => 'Surat Edaran Organisasi',
            'status' => 'published',
            'file' => UploadedFile::fake()->create('surat-edaran.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('admin.downloads.index'));

        $this->get(route('unduhan'))
            ->assertOk()
            ->assertSee('Surat Edaran Organisasi');

        $this->get(route('unduhan.download', 'surat-edaran-organisasi'))
            ->assertOk()
            ->assertDownload('surat-edaran.pdf');
    }
}
