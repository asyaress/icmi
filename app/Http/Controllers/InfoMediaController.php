<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InfoMediaController extends Controller
{
    public const CATEGORY_SIARAN_PERS = 'siaran-pers';
    public const CATEGORY_KABAR_ICMI = 'kabar-icmi';

    /**
     * @var array<int, string>
     */
    private const ALLOWED_CATEGORY_SLUGS = [
        self::CATEGORY_SIARAN_PERS,
        self::CATEGORY_KABAR_ICMI,
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = trim((string) $request->query('category', ''));
        if ($categorySlug !== '' && ! in_array($categorySlug, self::ALLOWED_CATEGORY_SLUGS, true)) {
            $categorySlug = '';
        }

        $posts = Post::query()
            ->type(Post::TYPE_MEDIA_INFO)
            ->with(['category', 'author', 'tags'])
            ->published()
            ->whereHas('category', function ($categoryQuery): void {
                $categoryQuery->whereIn('slug', self::ALLOWED_CATEGORY_SLUGS);
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug): void {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug): void {
                    $categoryQuery->where('slug', $categorySlug);
                });
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::query()
            ->whereHas('posts', function ($query): void {
                $query->type(Post::TYPE_MEDIA_INFO)->published();
            })
            ->whereIn('slug', self::ALLOWED_CATEGORY_SLUGS)
            ->orderByRaw("CASE WHEN slug = ? THEN 1 WHEN slug = ? THEN 2 ELSE 99 END", self::ALLOWED_CATEGORY_SLUGS)
            ->get();

        return view('info-media.index', compact('posts', 'categories', 'search', 'categorySlug'));
    }

    public function show(string $slug): View
    {
        $payload = PublicCache::remember("media-info:show:{$slug}", function () use ($slug): array {
            $post = Post::query()
                ->type(Post::TYPE_MEDIA_INFO)
                ->with(['category', 'author', 'tags'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            $relatedPosts = Post::query()
                ->type(Post::TYPE_MEDIA_INFO)
                ->published()
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->latest('published_at')
                ->limit(4)
                ->get();

            return compact('post', 'relatedPosts');
        });

        return view('info-media.show', $payload);
    }
}
