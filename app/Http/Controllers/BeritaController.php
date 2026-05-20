<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = trim((string) $request->query('category', ''));
        $locale = app()->getLocale();

        $posts = Post::query()
            ->type(Post::TYPE_NEWS)
            ->with(['category.translations', 'author', 'tags', 'translations'])
            ->published()
            ->when($search !== '', function ($query) use ($search, $locale): void {
                $query->where(function ($innerQuery) use ($search, $locale): void {
                    $innerQuery->where('title', 'like', "%{$search}%");
                    if ($locale === 'en') {
                        $innerQuery->orWhereHas('translations', function ($translationQuery) use ($search): void {
                            $translationQuery
                                ->where('locale', 'en')
                                ->where('field', 'title')
                                ->where('value', 'like', "%{$search}%");
                        });
                    }
                });
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
                $query->type(Post::TYPE_NEWS)->published();
            })
            ->with('translations')->orderBy('name')->get();

        return view('berita.index', compact('posts', 'categories', 'search', 'categorySlug'));
    }

    public function show(string $slug): View
    {
        $locale = app()->getLocale();
        $payload = PublicCache::remember("berita:show:{$slug}:{$locale}", function () use ($slug): array {
            $post = Post::query()
                ->type(Post::TYPE_NEWS)
                ->with(['category.translations', 'author', 'tags', 'translations'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            $relatedPosts = Post::query()
                ->type(Post::TYPE_NEWS)
                ->published()
                ->with(['translations', 'category.translations'])
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->latest('published_at')
                ->limit(4)
                ->get();

            return compact('post', 'relatedPosts');
        });

        return view('berita.show', $payload);
    }
}

