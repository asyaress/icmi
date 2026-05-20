<?php

namespace App\Observers;

use App\Jobs\TranslateModelContentJob;
use App\Support\Translation\TranslationModelMap;
use Illuminate\Database\Eloquent\Model;

class ContentTranslationObserver
{
    public function created(Model $model): void
    {
        $this->dispatch($model);
    }

    public function updated(Model $model): void
    {
        $fields = TranslationModelMap::fieldsFor($model);
        if ($fields === []) {
            return;
        }

        if (!$model->wasChanged(array_merge($fields, ['status']))) {
            return;
        }

        $this->dispatch($model);
    }

    public function restored(Model $model): void
    {
        $this->dispatch($model);
    }

    public function deleted(Model $model): void
    {
        if (method_exists($model, 'translations')) {
            $model->translations()->delete();
        }
    }

    private function dispatch(Model $model): void
    {
        if (!config('translation.enabled', true)) {
            return;
        }

        if (!TranslationModelMap::supports($model)) {
            return;
        }

        $targetLocales = config('translation.target_locales', ['en']);
        foreach ((array) $targetLocales as $targetLocale) {
            $targetLocale = strtolower(trim((string) $targetLocale));
            if ($targetLocale === '') {
                continue;
            }

            TranslateModelContentJob::dispatch($model::class, (int) $model->getKey(), $targetLocale)->afterCommit();
        }
    }
}
