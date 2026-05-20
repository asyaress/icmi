<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\HomeSettingController;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Video;
use App\Support\PublicCache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        $payload = PublicCache::remember("home:payload:{$locale}", function (): array {
            $config = $this->loadHomeConfig();

            $heroPosts = collect();
            if ($config['show_hero']) {
                $heroPosts = Post::query()
                    ->type(Post::TYPE_NEWS)
                    ->published()
                    ->with(['category.translations', 'author', 'translations'])
                    ->latest('published_at')
                    ->limit($config['hero_limit'])
                    ->get();
            }

            $trendingPosts = collect();
            if ($config['show_trending']) {
                $trendingPosts = Post::query()
                    ->published()
                    ->with(['category.translations', 'author', 'translations'])
                    ->latest('published_at')
                    ->limit($config['trending_limit'])
                    ->get();
            }

            $featuredPosts = collect();
            if ($config['show_featured']) {
                $featuredPosts = Post::query()
                    ->type(Post::TYPE_NEWS)
                    ->published()
                    ->with(['category.translations', 'author', 'translations'])
                    ->whereNotNull('featured_image')
                    ->latest('published_at')
                    ->limit($config['featured_limit'])
                    ->get();
            }

            $videoPosts = collect();
            if ($config['show_video']) {
                $videoPosts = Video::query()
                    ->published()
                    ->with('translations')
                    ->latest('published_at')
                    ->limit($config['video_limit'])
                    ->get();
            }

            $trendingTodayPosts = collect();
            if ($config['show_trending_today']) {
                $trendingTodayPosts = Post::query()
                    ->published()
                    ->with(['category.translations', 'author', 'translations'])
                    ->latest('published_at')
                    ->limit($config['trending_today_limit'])
                    ->get();
            }

            $mainPosts = collect();
            if ($config['show_main_posts']) {
                $mainPosts = Post::query()
                    ->type(Post::TYPE_NEWS)
                    ->published()
                    ->with(['category.translations', 'author', 'translations'])
                    ->latest('published_at')
                    ->limit($config['main_posts_limit'])
                    ->get();
            }

            $categories = Category::query()
                ->whereHas('posts', function ($query): void {
                    $query->published();
                })
                ->with('translations')
                ->withCount(['posts as published_posts_count' => function ($query): void {
                    $query->published();
                }])
                ->orderByDesc('published_posts_count')
                ->limit(5)
                ->get();

            $latestGalleries = Gallery::query()
                ->published()
                ->with('translations')
                ->withCount('items')
                ->latest('published_at')
                ->limit(3)
                ->get();

            return [
                'homeConfig' => $config,
                'heroPosts' => $heroPosts,
                'trendingPosts' => $trendingPosts,
                'featuredPosts' => $featuredPosts,
                'videoPosts' => $videoPosts,
                'trendingTodayPosts' => $trendingTodayPosts,
                'mainPosts' => $mainPosts,
                'categories' => $categories,
                'latestGalleries' => $latestGalleries,
                'siteName' => Setting::get('site_name', HomeSettingController::DEFAULTS['site_name']),
                'siteTagline' => Setting::get('site_tagline', HomeSettingController::DEFAULTS['site_tagline']),
                'metaDescription' => Setting::get('meta_default_description', HomeSettingController::DEFAULTS['meta_default_description']),
            ];
        });

        return view('index', $payload);
    }

    private function loadHomeConfig(): array
    {
        return [
            'show_hero' => Setting::getBool('home_show_hero', true),
            'show_trending' => Setting::getBool('home_show_trending', true),
            'show_featured' => Setting::getBool('home_show_featured', true),
            'show_video' => Setting::getBool('home_show_video', true),
            'show_trending_today' => Setting::getBool('home_show_trending_today', true),
            'show_main_posts' => Setting::getBool('home_show_main_posts', true),
            'hero_limit' => max(1, Setting::getInt('home_hero_limit', 3)),
            'trending_limit' => max(3, Setting::getInt('home_trending_limit', 7)),
            'featured_limit' => max(2, Setting::getInt('home_featured_limit', 4)),
            'video_limit' => max(3, Setting::getInt('home_video_limit', 5)),
            'trending_today_limit' => max(2, Setting::getInt('home_trending_today_limit', 4)),
            'main_posts_limit' => max(3, Setting::getInt('home_main_posts_limit', 6)),
        ];
    }
}

