<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfilePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'menu_label' => ['nullable', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:255'],
            'menu_order' => ['nullable', 'integer', 'min:0'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'max:8192', 'mimes:pdf,doc,docx'],
        ];
    }
}
