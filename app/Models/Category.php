<?php

namespace App\Models;

use App\Models\Concerns\HasContentTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasContentTranslations;

    public const ICMI_DAERAH_SLUG = 'icmi-daerah';
    public const ICMI_PUSAT_SLUG = 'icmi-pusat';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function scopeIcmiNews(Builder $query): Builder
    {
        return $query->whereIn('slug', [
            self::ICMI_DAERAH_SLUG,
            self::ICMI_PUSAT_SLUG,
        ]);
    }
}
