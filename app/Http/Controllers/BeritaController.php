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

        $posts = Post::query()
            ->type(Post::TYPE_NEWS)
            ->with(['category', 'author', 'tags'])
            ->published()
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
                $query->type(Post::TYPE_NEWS)->published();
            })
            ->orderBy('name')
            ->get();

        return view('berita.index', compact('posts', 'categories', 'search', 'categorySlug'));
    }

    public function show(string $slug): View
    {
        $payload = PublicCache::remember("berita:show:{$slug}", function () use ($slug): array {
            $post = Post::query()
                ->type(Post::TYPE_NEWS)
                ->with(['category', 'author', 'tags'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            $relatedPosts = Post::query()
                ->type(Post::TYPE_NEWS)
                ->published()
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
