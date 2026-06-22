<?php

return [
    'enabled' => (bool) env('TRANSLATION_ENABLED', true),
    'provider' => env('TRANSLATION_PROVIDER', 'google_nmt'),
    'source_locale' => env('TRANSLATION_SOURCE_LOCALE', 'id'),
    'target_locales' => array_values(array_filter(array_map(
        static fn (string $locale): string => strtolower(trim($locale)),
        explode(',', (string) env('TRANSLATION_TARGET_LOCALES', 'en'))
    ))),
    'queue' => env('TRANSLATION_QUEUE', 'translations'),
    'monthly_limit' => max(1, (int) env('TRANSLATION_MONTHLY_LIMIT', 500000)),
    'google' => [
        'project_id' => env('GCP_PROJECT_ID'),
        'region' => env('GCP_TRANSLATE_REGION', 'global'),
        'model' => env('GCP_TRANSLATE_MODEL', 'general/nmt'),
    ],
    'models' => [
        App\Models\Category::class => ['name', 'description'],
        App\Models\Post::class => ['title', 'excerpt', 'content', 'seo_title', 'seo_description'],
        App\Models\ProfilePage::class => ['title', 'menu_label', 'excerpt', 'content', 'seo_title', 'seo_description'],
        App\Models\Video::class => ['title', 'description', 'seo_title', 'seo_description'],
        App\Models\Gallery::class => ['title', 'description', 'seo_title', 'seo_description'],
        App\Models\Download::class => ['title', 'description'],
    ],
];
