<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentTranslation extends Model
{
    protected $fillable = [
        'translatable_type',
        'translatable_id',
        'locale',
        'field',
        'value',
        'source_hash',
        'provider',
        'translated_at',
    ];

    protected function casts(): array
    {
        return [
            'translated_at' => 'datetime',
        ];
    }

    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }
}

