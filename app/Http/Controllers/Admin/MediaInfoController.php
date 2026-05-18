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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaInfoController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const ALLOWED_CATEGORY_SLUGS = [
        \App\Http\Controllers\InfoMediaController::CATEGORY_SIARAN_PERS,
        \App\Http\Controllers\InfoMediaController::CATEGORY_KABAR_ICMI,
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $posts = Post::query()
            ->type(Post::TYPE_MEDIA_INFO)
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

        return view('admin.media-info.index', compact('posts', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.media-info.create', [
            'categories' => $this->allowedCategories(),
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
            'category_id' => $this->resolveCategoryId((int) $request->input('category_id')),
            'author_id' => (int) $request->user()->id,
            'type' => Post::TYPE_MEDIA_INFO,
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

        return redirect()->route('admin.media-info.index')->with('success', 'Info media berhasil dibuat.');
    }

    public function edit(Post $media_info): View
    {
        abort_if($media_info->type !== Post::TYPE_MEDIA_INFO, 404);

        return view('admin.media-info.edit', [
            'post' => $media_info->load('tags'),
            'categories' => $this->allowedCategories(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $media_info): RedirectResponse
    {
        abort_if($media_info->type !== Post::TYPE_MEDIA_INFO, 404);

        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $payload = [
            'category_id' => $this->resolveCategoryId((int) $request->input('category_id')),
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Post::class, 'slug', $media_info->id),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : ($media_info->published_at ?? now()))
                : null,
        ];

        if ($request->hasFile('featured_image')) {
            if ($media_info->featured_image) {
                Storage::disk('public')->delete($media_info->featured_image);
            }
            $payload['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $media_info->update($payload);
        $media_info->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.media-info.index')->with('success', 'Info media berhasil diupdate.');
    }

    public function destroy(Post $media_info): RedirectResponse
    {
        abort_if($media_info->type !== Post::TYPE_MEDIA_INFO, 404);

        if ($media_info->featured_image) {
            Storage::disk('public')->delete($media_info->featured_image);
        }

        $media_info->delete();

        return redirect()->route('admin.media-info.index')->with('success', 'Info media berhasil dihapus.');
    }

    private function allowedCategories()
    {
        return Category::query()
            ->where('is_active', true)
            ->whereIn('slug', self::ALLOWED_CATEGORY_SLUGS)
            ->orderByRaw("CASE WHEN slug = ? THEN 1 WHEN slug = ? THEN 2 ELSE 99 END", self::ALLOWED_CATEGORY_SLUGS)
            ->get();
    }

    private function resolveCategoryId(int $requestedCategoryId): int
    {
        $allowedIds = Category::query()
            ->whereIn('slug', self::ALLOWED_CATEGORY_SLUGS)
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->all();

        if (in_array($requestedCategoryId, $allowedIds, true)) {
            return $requestedCategoryId;
        }

        return $allowedIds[0] ?? $requestedCategoryId;
    }
}
