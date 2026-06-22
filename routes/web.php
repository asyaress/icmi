<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DownloadController as AdminDownloadController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\HomeSettingController as AdminHomeSettingController;
use App\Http\Controllers\Admin\MediaManagerController as AdminMediaManagerController;
use App\Http\Controllers\Admin\MediaInfoController as AdminMediaInfoController;
use App\Http\Controllers\Admin\OpinionController as AdminOpinionController;
use App\Http\Controllers\Admin\ProfilePageController as AdminProfilePageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IcmiAssetController;
use App\Http\Controllers\IcmiTvController;
use App\Http\Controllers\InfoMediaController;
use App\Http\Controllers\OpiniTokohController;
use App\Http\Controllers\ProfilePageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UnduhanController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/icmi-assets/{asset}', IcmiAssetController::class)
    ->where('asset', '[A-Za-z0-9.-]+')
    ->name('icmi-assets');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', function () {
    $content = implode("\n", [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        'Sitemap: ' . route('sitemap'),
        '',
    ]);

    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/api/weather/kaltim', WeatherController::class)->name('weather.kaltim');

Route::get('/about-us', function () {
    return view('pages.about-us');
})->name('about-us');

Route::get('/archived', function () {
    return view('pages.archived');
})->name('archived');

Route::get('/author', function () {
    return view('pages.author');
})->name('author');

Route::get('/blog-details-1', function () {
    return view('pages.blog-details-1');
})->name('blog-details-1');

Route::get('/blog-details-2', function () {
    return view('pages.blog-details-2');
})->name('blog-details-2');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// ICMI-style menu aliases
Route::get('/sekilas-icmi', [ProfilePageController::class, 'index'])->name('sekilas-icmi');
Route::get('/sekilas-icmi/{slug}', [ProfilePageController::class, 'show'])->name('sekilas-icmi.show');

Route::get('/info-media', [InfoMediaController::class, 'index'])->name('info-media');
Route::get('/info-media/{slug}', [InfoMediaController::class, 'show'])->name('info-media.show');

Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

Route::get('/opini-tokoh', [OpiniTokohController::class, 'index'])->name('opini-tokoh');
Route::get('/opini-tokoh/{slug}', [OpiniTokohController::class, 'show'])->name('opini-tokoh.show');

Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
Route::get('/galeri/{slug}', [GaleriController::class, 'show'])->name('galeri.show');

Route::get('/icmi-tv', [IcmiTvController::class, 'index'])->name('icmi-tv');
Route::get('/icmi-tv/{slug}', [IcmiTvController::class, 'show'])->name('icmi-tv.show');
Route::get('/unduhan', [UnduhanController::class, 'index'])->name('unduhan');
Route::get('/unduhan/{slug}/preview', [UnduhanController::class, 'preview'])->name('unduhan.preview');
Route::get('/unduhan/{slug}/download', [UnduhanController::class, 'download'])->name('unduhan.download');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:admin-login')
            ->name('login.store');
    });

    Route::middleware('auth')->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', AdminDashboardController::class)
            ->middleware('role:super-admin,admin,editor,contributor')
            ->name('dashboard');

        Route::resource('users', AdminUserController::class)
            ->except('show')
            ->middleware('role:super-admin,admin');

        Route::resource('categories', AdminCategoryController::class)
            ->except('show')
            ->middleware('role:super-admin,admin,editor');

        Route::resource('tags', AdminTagController::class)
            ->except('show')
            ->middleware('role:super-admin,admin,editor');

        Route::resource('posts', AdminPostController::class)
            ->except('show')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::resource('opinions', AdminOpinionController::class)
            ->parameters(['opinions' => 'opinion'])
            ->except('show')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::resource('media-info', AdminMediaInfoController::class)
            ->parameters(['media-info' => 'media_info'])
            ->except('show')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::resource('galleries', AdminGalleryController::class)
            ->parameters(['galleries' => 'gallery'])
            ->except('show')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::resource('videos', AdminVideoController::class)
            ->parameters(['videos' => 'video'])
            ->except('show')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::resource('downloads', AdminDownloadController::class)
            ->parameters(['downloads' => 'download'])
            ->except('show')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::get('/media-manager/library', [AdminMediaManagerController::class, 'library'])
            ->name('media-manager.library')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::get('/media-manager', [AdminMediaManagerController::class, 'index'])
            ->name('media-manager.index')
            ->middleware('role:super-admin,admin,editor,contributor');
        Route::post('/media-manager', [AdminMediaManagerController::class, 'store'])
            ->name('media-manager.store')
            ->middleware('role:super-admin,admin,editor,contributor');
        Route::delete('/media-manager/{media_file}', [AdminMediaManagerController::class, 'destroy'])
            ->name('media-manager.destroy')
            ->middleware('role:super-admin,admin,editor,contributor');

        Route::resource('profile-pages', AdminProfilePageController::class)
            ->parameters(['profile-pages' => 'profile_page'])
            ->except('show')
            ->middleware('role:super-admin,admin,editor');

        Route::get('/settings/home', [AdminHomeSettingController::class, 'edit'])
            ->name('settings.home.edit')
            ->middleware('role:super-admin,admin,editor');
        Route::put('/settings/home', [AdminHomeSettingController::class, 'update'])
            ->name('settings.home.update')
            ->middleware('role:super-admin,admin,editor');
    });
});
