<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IcmiTvController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $locale = app()->getLocale();

        $baseQuery = Video::query()
            ->with(['author', 'translations'])
            ->published()
            ->when($search !== '', function ($query) use ($search, $locale): void {
                $query->where(function ($innerQuery) use ($search, $locale): void {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");

                    if ($locale === 'en') {
                        $innerQuery->orWhereHas('translations', function ($translationQuery) use ($search): void {
                            $translationQuery
                                ->where('locale', 'en')
                                ->whereIn('field', ['title', 'description'])
                                ->where('value', 'like', "%{$search}%");
                        });
                    }
                });
            });

        $featuredVideos = (clone $baseQuery)
            ->latest('published_at')
            ->limit(5)
            ->get();

        $programVideos = (clone $baseQuery)
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('icmi-tv.index', compact('featuredVideos', 'programVideos', 'search'));
    }

    public function show(string $slug): View
    {
        $locale = app()->getLocale();
        $payload = PublicCache::remember("icmi-tv:show:{$slug}:{$locale}", function () use ($slug): array {
            $video = Video::query()
                ->with(['author', 'translations'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            $relatedVideos = Video::query()
                ->published()
                ->with('translations')
                ->where('id', '!=', $video->id)
                ->latest('published_at')
                ->limit(5)
                ->get();

            return compact('video', 'relatedVideos');
        });

        return view('icmi-tv.show', $payload);
    }
}
