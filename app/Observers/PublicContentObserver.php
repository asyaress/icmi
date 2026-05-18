<?php

namespace App\Observers;

use App\Support\PublicCache;

class PublicContentObserver
{
    public function created(object $model): void
    {
        PublicCache::flush();
    }

    public function updated(object $model): void
    {
        PublicCache::flush();
    }

    public function deleted(object $model): void
    {
        PublicCache::flush();
    }

    public function restored(object $model): void
    {
        PublicCache::flush();
    }
}
