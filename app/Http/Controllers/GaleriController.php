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
        $locale = app()->getLocale();

        $galleries = Gallery::query()
            ->with('translations')
            ->withCount('items')
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
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('galeri.index', compact('galleries', 'search'));
    }

    public function show(string $slug): View
    {
        $locale = app()->getLocale();
        $payload = PublicCache::remember("galeri:show:{$slug}:{$locale}", function () use ($slug): array {
            $gallery = Gallery::query()
                ->with(['items', 'translations'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            $relatedGalleries = Gallery::query()
                ->published()
                ->with('translations')
                ->where('id', '!=', $gallery->id)
                ->latest('published_at')
                ->limit(4)
                ->get();

            return compact('gallery', 'relatedGalleries');
        });

        return view('galeri.show', $payload);
    }
}
