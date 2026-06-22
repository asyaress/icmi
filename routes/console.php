<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use App\Support\PublicCache;
use App\Support\Translation\ContentTranslationService;
use App\Support\Translation\TranslationUsageLimiter;
use App\Support\Translation\Exceptions\TranslationQuotaExceededException;
use App\Jobs\TranslateModelContentJob;
use Illuminate\Database\Eloquent\Model;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('icmi:cache:flush', function () {
    PublicCache::flush();
    $this->info('Public cache version bumped. Cache lama otomatis invalid.');
})->purpose('Invalidate public cache layer');

Artisan::command('icmi:cache:warm', function () {
    $baseUrl = config('app.url', 'http://localhost');
    $paths = ['/', '/sitemap.xml', '/berita', '/opini-tokoh', '/info-media', '/galeri', '/unduhan', '/icmi-tv', '/api/weather/kaltim'];

    foreach ($paths as $path) {
        $url = rtrim($baseUrl, '/') . $path;
        $status = rescue(fn () => Http::timeout(15)->get($url)->status(), 0, report: false);
        $this->line(sprintf('[%s] %s', $status, $url));
    }

    $this->info('Cache warm-up selesai.');
})->purpose('Warm up critical public routes cache');

Artisan::command('icmi:translate:test {text=Halo dunia} {--from=id} {--to=en}', function (ContentTranslationService $translationService) {
    $text = (string) $this->argument('text');
    $from = strtolower((string) $this->option('from'));
    $to = strtolower((string) $this->option('to'));

    try {
        $translated = $translationService->translateTextForTest($text, $from, $to);
        $this->info('Translate OK');
        $this->line('Source: ' . $text);
        $this->line('Target: ' . $translated);
    } catch (TranslationQuotaExceededException $e) {
        $this->warn($e->getMessage());
    }
})->purpose('Test Google NMT translation and count quota usage.');

Artisan::command('icmi:translate:usage', function (TranslationUsageLimiter $usageLimiter) {
    $provider = (string) config('translation.provider', 'google_nmt');
    $usage = $usageLimiter->usage($provider);

    $this->line('Period   : ' . $usage['period']);
    $this->line('Provider : ' . $usage['provider']);
    $this->line('Used     : ' . number_format((int) $usage['used_characters']));
    $this->line('Limit    : ' . number_format((int) $usage['monthly_limit']));
    $this->line('Remain   : ' . number_format((int) $usage['remaining_characters']));
    $this->line('Blocked  : ' . ($usage['blocked'] ? 'yes' : 'no'));
})->purpose('Show translation monthly usage and remaining quota.');

Artisan::command('icmi:translate:sync {--now : Proses langsung tanpa antre queue}', function (ContentTranslationService $translationService) {
    $targetLocales = (array) config('translation.target_locales', ['en']);
    $modelMap = (array) config('translation.models', []);
    $runNow = (bool) $this->option('now');

    $dispatched = 0;
    $processed = 0;

    foreach (array_keys($modelMap) as $modelClass) {
        if (!class_exists($modelClass) || !is_subclass_of($modelClass, Model::class)) {
            continue;
        }

        $query = $modelClass::query();
        if (method_exists($modelClass, 'scopePublished')) {
            $query->published();
        }

        $query->select('id')->orderBy('id')->chunkById(100, function ($rows) use (&$dispatched, &$processed, $targetLocales, $runNow, $modelClass, $translationService): void {
            foreach ($rows as $row) {
                foreach ($targetLocales as $targetLocale) {
                    $targetLocale = strtolower(trim((string) $targetLocale));
                    if ($targetLocale === '') {
                        continue;
                    }

                    if ($runNow) {
                        $model = $modelClass::query()->find($row->id);
                        if ($model) {
                            $translationService->translateModel($model, $targetLocale);
                            $processed++;
                        }
                    } else {
                        TranslateModelContentJob::dispatch($modelClass, (int) $row->id, $targetLocale);
                        $dispatched++;
                    }
                }
            }
        });
    }

    if ($runNow) {
        $this->info("Translate sync selesai. Processed: {$processed}");
    } else {
        $this->info("Translate sync diantrekan. Jobs: {$dispatched}");
    }
})->purpose('Queue or run translation sync for published content.');
