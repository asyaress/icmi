<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasRole(['super-admin', 'admin', 'editor']);
    }

    public function rules(): array
    {
        return [
            'site_name' => ['nullable', 'string', 'max:100'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'meta_default_title' => ['nullable', 'string', 'max:255'],
            'meta_default_description' => ['nullable', 'string', 'max:320'],
            'meta_default_keywords' => ['nullable', 'string', 'max:500'],

            'home_show_hero' => ['nullable', 'boolean'],
            'home_show_trending' => ['nullable', 'boolean'],
            'home_show_featured' => ['nullable', 'boolean'],
            'home_show_video' => ['nullable', 'boolean'],
            'home_show_trending_today' => ['nullable', 'boolean'],
            'home_show_main_posts' => ['nullable', 'boolean'],

            'home_hero_limit' => ['required', 'integer', 'min:1', 'max:10'],
            'home_trending_limit' => ['required', 'integer', 'min:3', 'max:20'],
            'home_featured_limit' => ['required', 'integer', 'min:2', 'max:12'],
            'home_video_limit' => ['required', 'integer', 'min:3', 'max:12'],
            'home_trending_today_limit' => ['required', 'integer', 'min:2', 'max:12'],
            'home_main_posts_limit' => ['required', 'integer', 'min:3', 'max:18'],
        ];
    }
}
