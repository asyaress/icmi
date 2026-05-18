<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use App\Support\PublicCache;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('icmi:cache:flush', function () {
    PublicCache::flush();
    $this->info('Public cache version bumped. Cache lama otomatis invalid.');
})->purpose('Invalidate public cache layer');

Artisan::command('icmi:cache:warm', function () {
    $baseUrl = config('app.url', 'http://localhost');
    $paths = ['/', '/sitemap.xml', '/berita', '/opini-tokoh', '/info-media', '/galeri', '/icmi-tv', '/api/weather/kaltim'];

    foreach ($paths as $path) {
        $url = rtrim($baseUrl, '/') . $path;
        $status = rescue(fn () => Http::timeout(15)->get($url)->status(), 0, report: false);
        $this->line(sprintf('[%s] %s', $status, $url));
    }

    $this->info('Cache warm-up selesai.');
})->purpose('Warm up critical public routes cache');
