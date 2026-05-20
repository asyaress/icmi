<?php

namespace App\Jobs;

use App\Support\Translation\ContentTranslationService;
use App\Support\Translation\Exceptions\TranslationQuotaExceededException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TranslateModelContentJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public string $modelClass,
        public int $modelId,
        public string $targetLocale = 'en'
    ) {
        $this->onQueue((string) config('translation.queue', 'translations'));
    }

    public function handle(ContentTranslationService $translationService): void
    {
        if (!class_exists($this->modelClass)) {
            return;
        }

        /** @var Model|null $model */
        $model = $this->modelClass::query()->find($this->modelId);
        if ($model === null) {
            return;
        }

        try {
            $translationService->translateModel($model, $this->targetLocale);
        } catch (TranslationQuotaExceededException $e) {
            Log::warning('Translation quota exceeded, stopping further calls for this job.', [
                'model' => $this->modelClass,
                'model_id' => $this->modelId,
                'target_locale' => $this->targetLocale,
            ]);
        }
    }
}

