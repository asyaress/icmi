<?php

namespace App\Models\Concerns;

use App\Models\ContentTranslation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContentTranslations
{
    public function translations(): MorphMany
    {
        return $this->morphMany(ContentTranslation::class, 'translatable');
    }

    public function translated(string $field, ?string $locale = null): ?string
    {
        $locale = strtolower((string) ($locale ?? app()->getLocale()));
        $sourceLocale = strtolower((string) config('translation.source_locale', 'id'));
        $fallback = $this->rawFieldValue($field);

        if ($locale === '' || $locale === $sourceLocale) {
            return $fallback;
        }

        $translation = $this->findTranslationValue($field, $locale);

        return $translation !== null && $translation !== ''
            ? $translation
            : $fallback;
    }

    private function rawFieldValue(string $field): ?string
    {
        $value = $this->getAttribute($field);

        if ($value === null) {
            return null;
        }

        return (string) $value;
    }

    private function findTranslationValue(string $field, string $locale): ?string
    {
        if ($this->relationLoaded('translations')) {
            $item = $this->translations
                ->first(fn (ContentTranslation $translation): bool => $translation->field === $field && $translation->locale === $locale);

            return $item?->value;
        }

        return $this->translations()
            ->where('field', $field)
            ->where('locale', $locale)
            ->value('value');
    }
}

