<?php

namespace App\Support\Translation;

use Google\Cloud\Translate\V3\TranslationServiceClient;
use RuntimeException;

class GoogleNmtTranslator
{
    public function translate(string $text, string $sourceLocale, string $targetLocale, bool $isHtml = false): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        $projectId = (string) config('translation.google.project_id');
        $region = (string) config('translation.google.region', 'global');
        $model = (string) config('translation.google.model', 'general/nmt');

        if ($projectId === '') {
            throw new RuntimeException('GCP_PROJECT_ID belum di-set.');
        }

        $parent = sprintf('projects/%s/locations/%s', $projectId, $region);
        $modelPath = sprintf('%s/models/%s', $parent, $model);

        $client = new TranslationServiceClient();

        try {
            $response = $client->translateText(
                [$text],
                $targetLocale,
                $parent,
                [
                    'sourceLanguageCode' => $sourceLocale,
                    'mimeType' => $isHtml ? 'text/html' : 'text/plain',
                    'model' => $modelPath,
                ]
            );
            $translations = $response->getTranslations();

            if (!isset($translations[0])) {
                throw new RuntimeException('Respons translate kosong.');
            }

            return (string) $translations[0]->getTranslatedText();
        } finally {
            $client->close();
        }
    }
}
