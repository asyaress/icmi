<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\ProfilePage;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\Video;
use App\Observers\ContentTranslationObserver;
use App\Observers\PublicContentObserver;
use App\Support\Weather\KaltimWeatherService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request): Limit {
            $email = (string) $request->input('email', 'guest');

            return Limit::perMinute(5)->by(strtolower($email) . '|' . $request->ip());
        });

        Post::observe(PublicContentObserver::class);
        Category::observe(PublicContentObserver::class);
        Tag::observe(PublicContentObserver::class);
        Gallery::observe(PublicContentObserver::class);
        Video::observe(PublicContentObserver::class);
        ProfilePage::observe(PublicContentObserver::class);
        Setting::observe(PublicContentObserver::class);

        Post::observe(ContentTranslationObserver::class);
        Category::observe(ContentTranslationObserver::class);
        ProfilePage::observe(ContentTranslationObserver::class);
        Video::observe(ContentTranslationObserver::class);
        Gallery::observe(ContentTranslationObserver::class);

        View::composer('partials.home.sections.hero', function ($view): void {
            $weatherItems = app(KaltimWeatherService::class)->getItemsCachedOnly((string) app()->getLocale());
            $view->with('weatherItems', $weatherItems);
        });
    }
}
