<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaFile extends Model
{
    protected $fillable = [
        'uploader_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'extension',
        'size',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function getUrlAttribute(): string
    {
        if ($this->disk === 'public') {
            return asset('storage/' . ltrim($this->path, '/'));
        }

        return asset($this->path);
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }
}
