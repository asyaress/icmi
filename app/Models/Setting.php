<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $value = static::query()->where('key', $key)->value('value');
        } catch (\Throwable) {
            return $default;
        }

        return $value ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['group' => $group, 'value' => is_scalar($value) || $value === null ? (string) $value : json_encode($value)]
        );
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $value = static::get($key, $default ? '1' : '0');

        return in_array((string) $value, ['1', 'true', 'yes', 'on'], true);
    }

    public static function getInt(string $key, int $default = 0): int
    {
        return (int) static::get($key, (string) $default);
    }
}
