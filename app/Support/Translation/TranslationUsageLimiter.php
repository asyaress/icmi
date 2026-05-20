<?php

namespace App\Support\Translation;

use App\Models\TranslationUsage;
use Illuminate\Support\Facades\DB;

class TranslationUsageLimiter
{
    public function currentPeriod(): string
    {
        return now()->format('Y-m');
    }

    public function claim(string $provider, int $characters): bool
    {
        if ($characters <= 0) {
            return true;
        }

        $period = $this->currentPeriod();
        $monthlyLimit = max(1, (int) config('translation.monthly_limit', 500000));

        return DB::transaction(function () use ($provider, $characters, $period, $monthlyLimit): bool {
            $usage = TranslationUsage::query()
                ->where('period', $period)
                ->where('provider', $provider)
                ->lockForUpdate()
                ->first();

            if ($usage === null) {
                $usage = TranslationUsage::query()->create([
                    'period' => $period,
                    'provider' => $provider,
                    'used_characters' => 0,
                    'monthly_limit' => $monthlyLimit,
                ]);
            }

            if (($usage->used_characters + $characters) > $usage->monthly_limit) {
                if ($usage->blocked_at === null) {
                    $usage->blocked_at = now();
                    $usage->save();
                }

                return false;
            }

            $usage->used_characters += $characters;
            $usage->blocked_at = null;
            $usage->save();

            return true;
        });
    }

    public function release(string $provider, int $characters): void
    {
        if ($characters <= 0) {
            return;
        }

        $period = $this->currentPeriod();

        DB::transaction(function () use ($provider, $characters, $period): void {
            $usage = TranslationUsage::query()
                ->where('period', $period)
                ->where('provider', $provider)
                ->lockForUpdate()
                ->first();

            if ($usage === null) {
                return;
            }

            $usage->used_characters = max(0, (int) $usage->used_characters - $characters);
            if ($usage->used_characters < $usage->monthly_limit) {
                $usage->blocked_at = null;
            }
            $usage->save();
        });
    }

    public function usage(string $provider): array
    {
        $period = $this->currentPeriod();
        $monthlyLimit = max(1, (int) config('translation.monthly_limit', 500000));
        $usage = TranslationUsage::query()
            ->where('period', $period)
            ->where('provider', $provider)
            ->first();

        $used = (int) ($usage->used_characters ?? 0);
        $limit = (int) ($usage->monthly_limit ?? $monthlyLimit);

        return [
            'period' => $period,
            'provider' => $provider,
            'used_characters' => $used,
            'monthly_limit' => $limit,
            'remaining_characters' => max(0, $limit - $used),
            'blocked' => $used >= $limit,
            'blocked_at' => $usage?->blocked_at,
        ];
    }
}

