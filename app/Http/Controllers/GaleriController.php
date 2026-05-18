<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $galleries = Gallery::query()
            ->withCount('items')
            ->published()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('galeri.index', compact('galleries', 'search'));
    }

    public function show(string $slug): View
    {
        $payload = PublicCache::remember("galeri:show:{$slug}", function () use ($slug): array {
            $gallery = Gallery::query()
                ->with('items')
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            $relatedGalleries = Gallery::query()
                ->published()
                ->where('id', '!=', $gallery->id)
                ->latest('published_at')
                ->limit(4)
                ->get();

            return compact('gallery', 'relatedGalleries');
        });

        return view('galeri.show', $payload);
    }
}
