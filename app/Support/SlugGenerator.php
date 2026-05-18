<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugGenerator
{
    public static function generate(string $value, string $modelClass, string $column = 'slug', ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'item';

        $slug = $baseSlug;
        $counter = 1;

        while (self::slugExists($slug, $modelClass, $column, $ignoreId)) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    protected static function slugExists(string $slug, string $modelClass, string $column, ?int $ignoreId): bool
    {
        $query = $modelClass::query()->where($column, $slug);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        return $query->exists();
    }
}

