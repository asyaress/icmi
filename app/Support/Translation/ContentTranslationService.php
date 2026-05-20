<?php

namespace App\Support\Translation;

use App\Models\ContentTranslation;
use App\Support\PublicCache;
use App\Support\Translation\Exceptions\TranslationQuotaExceededException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContentTranslationService
{
    public function __construct(
        private readonly GoogleNmtTranslator $translator,
        private readonly TranslationUsageLimiter $usageLimiter
    ) {
    }

    public function translateModel(Model $model, string $targetLocale = 'en'): array
    {
        if (!config('translation.enabled', true)) {
            return ['translated' => 0, 'skipped' => 0, 'quota_exceeded' => false];
        }

        if (!method_exists($model, 'translations')) {
            return ['translated' => 0, 'skipped' => 0, 'quota_exceeded' => false];
        }

        $sourceLocale = strtolower((string) config('translation.source_locale', 'id'));
        $targetLocale = strtolower(trim($targetLocale));
        if ($targetLocale === '' || $targetLocale === $sourceLocale) {
            return ['translated' => 0, 'skipped' => 0, 'quota_exceeded' => false];
        }

        $fields = TranslationModelMap::fieldsFor($model);
        if ($fields === []) {
            return ['translated' => 0, 'skipped' => 0, 'quota_exceeded' => false];
        }

        $provider = (string) config('translation.provider', 'google_nmt');
        $translatedCount = 0;
        $skippedCount = 0;
        $quotaExceeded = false;
        $changed = false;

        $existing = $model->translations()
            ->where('locale', $targetLocale)
            ->whereIn('field', $fields)
            ->get()
            ->keyBy('field');

        foreach ($fields as $field) {
            $rawValue = $model->getAttribute($field);
            $sourceText = trim((string) ($rawValue ?? ''));

            if ($sourceText === '') {
                $skippedCount++;
                continue;
            }

            $sourceHash = hash('sha256', $sourceText);
            /** @var ContentTranslation|null $current */
            $current = $existing->get($field);
            if ($current && $current->source_hash === $sourceHash && trim((string) $current->value) !== '') {
                $skippedCount++;
                continue;
            }

            $characters = mb_strlen($sourceText);
            if (!$this->usageLimiter->claim($provider, $characters)) {
                $quotaExceeded = true;
                Log::warning('Translation skipped due to monthly quota limit.', [
                    'model' => $model::class,
                    'model_id' => $model->getKey(),
                    'field' => $field,
                    'period' => $this->usageLimiter->currentPeriod(),
                ]);
                break;
            }

            try {
                $translated = $this->translator->translate(
                    $sourceText,
                    $sourceLocale,
                    $targetLocale,
                    $this->looksLikeHtml($sourceText)
                );
            } catch (\Throwable $e) {
                $this->usageLimiter->release($provider, $characters);
                throw $e;
            }

            $model->translations()->updateOrCreate(
                [
                    'locale' => $targetLocale,
                    'field' => $field,
                ],
                [
                    'value' => $translated,
                    'source_hash' => $sourceHash,
                    'provider' => $provider,
                    'translated_at' => now(),
                ]
            );

            $translatedCount++;
            $changed = true;
        }

        if ($changed) {
            PublicCache::flush();
        }

        if ($quotaExceeded) {
            throw new TranslationQuotaExceededException('Kuota translate bulanan sudah mencapai batas.');
        }

        return [
            'translated' => $translatedCount,
            'skipped' => $skippedCount,
            'quota_exceeded' => false,
        ];
    }

    public function translateTextForTest(string $text, string $from = 'id', string $to = 'en'): string
    {
        $provider = (string) config('translation.provider', 'google_nmt');
        $characters = mb_strlen($text);

        if (!$this->usageLimiter->claim($provider, $characters)) {
            throw new TranslationQuotaExceededException('Kuota bulanan 500.000 karakter sudah habis.');
        }

        try {
            return $this->translator->translate($text, $from, $to, $this->looksLikeHtml($text));
        } catch (\Throwable $e) {
            $this->usageLimiter->release($provider, $characters);
            throw $e;
        }
    }

    private function looksLikeHtml(string $value): bool
    {
        return $value !== strip_tags($value) || Str::contains($value, ['<p', '<div', '<br', '<h1', '<h2', '<h3', '<ul', '<ol', '<li']);
    }
}
