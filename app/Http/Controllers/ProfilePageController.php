<?php

namespace App\Http\Controllers;

use App\Models\ProfilePage;
use App\Support\PublicCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilePageController extends Controller
{
    public function index(): RedirectResponse
    {
        $firstPage = ProfilePage::query()
            ->published()
            ->with('translations')
            ->orderBy('menu_order')
            ->firstOrFail();

        return redirect()->route('sekilas-icmi.show', $firstPage->slug);
    }

    public function show(string $slug): View
    {
        $locale = app()->getLocale();
        $payload = PublicCache::remember("profile:show:{$slug}:{$locale}", function () use ($slug): array {
            $page = ProfilePage::query()
                ->published()
                ->with('translations')
                ->where('slug', $slug)
                ->firstOrFail();

            $menuPages = ProfilePage::query()
                ->published()
                ->orderBy('menu_order')
                ->with('translations')
                ->get(['id', 'title', 'slug', 'menu_label']);

            return compact('page', 'menuPages');
        });

        return view('sekilas-icmi.show', $payload);
    }
}
