<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\SlugGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $posts = Post::query()
            ->type(Post::TYPE_NEWS)
            ->with(['category', 'author'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when(in_array($status, ['draft', 'published'], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => $this->newsCategories(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        $post = Post::query()->create([
            'category_id' => (int) $request->input('category_id'),
            'author_id' => (int) $request->user()->id,
            'type' => Post::TYPE_NEWS,
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Post::class),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'featured_image' => $imagePath,
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : now())
                : null,
        ]);

        $post->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(Post $post): View
    {
        abort_if($post->type !== Post::TYPE_NEWS, 404);

        return view('admin.posts.edit', [
            'post' => $post->load('tags'),
            'categories' => $this->newsCategories(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        abort_if($post->type !== Post::TYPE_NEWS, 404);

        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $payload = [
            'category_id' => (int) $request->input('category_id'),
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Post::class, 'slug', $post->id),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : ($post->published_at ?? now()))
                : null,
        ];

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $payload['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update($payload);
        $post->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diupdate.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        abort_if($post->type !== Post::TYPE_NEWS, 404);

        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function newsCategories(): Collection
    {
        return Category::query()
            ->icmiNews()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
