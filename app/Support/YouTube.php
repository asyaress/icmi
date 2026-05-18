<?php

namespace App\Support;

class YouTube
{
    public static function extractId(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $url) === 1) {
            return $url;
        }

        $parts = parse_url($url);
        if (!is_array($parts)) {
            return null;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = trim((string) ($parts['path'] ?? ''), '/');

        if (str_ends_with($host, 'youtu.be') && $path !== '') {
            return self::normalizeId($path);
        }

        if (str_contains($host, 'youtube.com')) {
            parse_str((string) ($parts['query'] ?? ''), $query);
            if (!empty($query['v']) && is_string($query['v'])) {
                return self::normalizeId($query['v']);
            }

            $segments = explode('/', $path);
            if (count($segments) >= 2 && in_array($segments[0], ['embed', 'shorts'], true)) {
                return self::normalizeId($segments[1]);
            }
        }

        return null;
    }

    private static function normalizeId(string $candidate): ?string
    {
        $candidate = trim($candidate);
        if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $candidate) !== 1) {
            return null;
        }

        return $candidate;
    }
}
