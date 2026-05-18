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

class OpinionController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const ALLOWED_CATEGORY_SLUGS = [
        \App\Http\Controllers\OpiniTokohController::CATEGORY_OPINI,
        \App\Http\Controllers\OpiniTokohController::CATEGORY_TOKOH,
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $posts = Post::query()
            ->type(Post::TYPE_OPINION)
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

        return view('admin.opinions.index', compact('posts', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.opinions.create', [
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
            'type' => Post::TYPE_OPINION,
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

        return redirect()->route('admin.opinions.index')->with('success', 'Opini berhasil dibuat.');
    }

    public function edit(Post $opinion): View
    {
        abort_if($opinion->type !== Post::TYPE_OPINION, 404);

        return view('admin.opinions.edit', [
            'post' => $opinion->load('tags'),
            'categories' => $this->allowedCategories(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $opinion): RedirectResponse
    {
        abort_if($opinion->type !== Post::TYPE_OPINION, 404);

        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $payload = [
            'category_id' => $this->resolveCategoryId((int) $request->input('category_id')),
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Post::class, 'slug', $opinion->id),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : ($opinion->published_at ?? now()))
                : null,
        ];

        if ($request->hasFile('featured_image')) {
            if ($opinion->featured_image) {
                Storage::disk('public')->delete($opinion->featured_image);
            }
            $payload['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $opinion->update($payload);
        $opinion->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.opinions.index')->with('success', 'Opini berhasil diupdate.');
    }

    public function destroy(Post $opinion): RedirectResponse
    {
        abort_if($opinion->type !== Post::TYPE_OPINION, 404);

        if ($opinion->featured_image) {
            Storage::disk('public')->delete($opinion->featured_image);
        }

        $opinion->delete();

        return redirect()->route('admin.opinions.index')->with('success', 'Opini berhasil dihapus.');
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
