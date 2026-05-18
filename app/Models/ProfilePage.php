<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilePage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'menu_label',
        'slug',
        'menu_order',
        'excerpt',
        'content',
        'seo_title',
        'seo_description',
        'attachment_path',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'menu_order' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
