<?php

namespace App\Support\Translation;

use Illuminate\Database\Eloquent\Model;

class TranslationModelMap
{
    /**
     * @return array<int, string>
     */
    public static function fieldsFor(Model|string $model): array
    {
        $class = is_string($model) ? $model : $model::class;
        $models = config('translation.models', []);
        $fields = $models[$class] ?? [];

        return array_values(array_filter(array_map(
            static fn ($field): string => trim((string) $field),
            is_array($fields) ? $fields : []
        )));
    }

    public static function supports(Model|string $model): bool
    {
        return self::fieldsFor($model) !== [];
    }
}

