<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationUsage extends Model
{
    protected $fillable = [
        'period',
        'provider',
        'used_characters',
        'monthly_limit',
        'blocked_at',
    ];

    protected function casts(): array
    {
        return [
            'used_characters' => 'integer',
            'monthly_limit' => 'integer',
            'blocked_at' => 'datetime',
        ];
    }
}

