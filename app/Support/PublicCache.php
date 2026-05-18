<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

class PublicCache
{
    public static function remember(string $key, Closure $callback, ?int $ttlSeconds = null): mixed
    {
        $version = self::version();
        $ttl = $ttlSeconds ?? (int) config('icmi.public_cache_ttl', 300);

        return Cache::remember(
            "public:v{$version}:{$key}",
            now()->addSeconds($ttl),
            $callback
        );
    }

    public static function flush(): void
    {
        if (!Cache::has('public_cache_version')) {
            Cache::forever('public_cache_version', 1);
        }

        Cache::increment('public_cache_version');
    }

    private static function version(): int
    {
        return (int) Cache::get('public_cache_version', 1);
    }
}
