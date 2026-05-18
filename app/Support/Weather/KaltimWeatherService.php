<?php

namespace App\Support\Weather;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class KaltimWeatherService
{
    /**
     * @var array<int, array{name:string,lat:float,lon:float}>
     */
    private const LOCATIONS = [
        ['name' => 'Kota Samarinda', 'lat' => -0.5022, 'lon' => 117.1536],
        ['name' => 'Kota Balikpapan', 'lat' => -1.2379, 'lon' => 116.8529],
        ['name' => 'Kota Bontang', 'lat' => 0.1333, 'lon' => 117.5000],
        ['name' => 'Kab. Kutai Kartanegara', 'lat' => -0.4120, 'lon' => 116.9895],
        ['name' => 'Kab. Kutai Timur', 'lat' => 0.5333, 'lon' => 117.4833],
        ['name' => 'Kab. Kutai Barat', 'lat' => -0.5833, 'lon' => 115.7000],
        ['name' => 'Kab. Paser', 'lat' => -1.8667, 'lon' => 116.1667],
        ['name' => 'Kab. Penajam Paser Utara', 'lat' => -1.2738, 'lon' => 116.6346],
        ['name' => 'Kab. Berau', 'lat' => 2.1500, 'lon' => 117.5000],
        ['name' => 'Kab. Mahakam Ulu', 'lat' => 0.4500, 'lon' => 115.4000],
    ];

    private const CACHE_KEY_PREFIX = 'weather:kaltim:current:v2';
    private const CACHE_SECONDS = 1200; // 20 menit

    /**
     * @return array<int, array{name:string,temp_c:string,condition:string,icon:string,updated_at:string}>
     */
    public function getItems(string $locale = 'id'): array
    {
        $locale = strtolower($locale) === 'en' ? 'en' : 'id';
        $cacheKey = self::CACHE_KEY_PREFIX . ':' . $locale;

        return Cache::remember($cacheKey, self::CACHE_SECONDS, fn (): array => $this->fetchBatch($locale));
    }

    /**
     * Return cached weather instantly. If cache is empty, return lightweight fallback.
     *
     * @return array<int, array{name:string,temp_c:string,condition:string,icon:string,updated_at:string}>
     */
    public function getItemsCachedOnly(string $locale = 'id'): array
    {
        $locale = strtolower($locale) === 'en' ? 'en' : 'id';
        $cacheKey = self::CACHE_KEY_PREFIX . ':' . $locale;
        $updatedAt = now('Asia/Makassar')->format('H:i');

        return Cache::get($cacheKey, $this->fallbackSet($locale, $updatedAt));
    }

    public function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY_PREFIX . ':id');
        Cache::forget(self::CACHE_KEY_PREFIX . ':en');
    }

    /**
     * @return array<int, array{name:string,temp_c:string,condition:string,icon:string,updated_at:string}>
     */
    private function fetchBatch(string $locale): array
    {
        $updatedAt = now('Asia/Makassar')->format('H:i');
        $fallback = $this->fallbackSet($locale, $updatedAt);

        try {
            $response = Http::timeout(5)->retry(1, 150)->acceptJson()->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => implode(',', array_column(self::LOCATIONS, 'lat')),
                'longitude' => implode(',', array_column(self::LOCATIONS, 'lon')),
                'current' => 'temperature_2m,weather_code,is_day',
                'timezone' => 'Asia/Makassar',
                'forecast_days' => 1,
            ]);

            if (! $response->ok()) {
                return $fallback;
            }

            $payload = $response->json();

            if (! is_array($payload)) {
                return $fallback;
            }

            // Open-Meteo: multiple coordinates returns an array of objects.
            if (isset($payload['current'])) {
                $payload = [$payload];
            }

            $items = [];
            foreach (self::LOCATIONS as $index => $location) {
                $record = $payload[$index] ?? null;
                if (! is_array($record)) {
                    $items[] = $fallback[$index];
                    continue;
                }

                $current = is_array($record['current'] ?? null) ? $record['current'] : [];
                $temperature = $current['temperature_2m'] ?? null;
                $weatherCode = is_numeric($current['weather_code'] ?? null) ? (int) $current['weather_code'] : -1;
                $isDay = (int) ($current['is_day'] ?? 1) === 1;

                [$condition, $icon] = $this->mapWeather($weatherCode, $isDay, $locale);

                $items[] = [
                    'name' => $location['name'],
                    'temp_c' => is_numeric($temperature) ? (string) round((float) $temperature) : '--',
                    'condition' => $condition,
                    'icon' => $icon,
                    'updated_at' => $updatedAt,
                ];
            }

            return $items;
        } catch (\Throwable) {
            return $fallback;
        }
    }

    /**
     * @return array<int, array{name:string,temp_c:string,condition:string,icon:string,updated_at:string}>
     */
    private function fallbackSet(string $locale, string $updatedAt): array
    {
        return array_map(
            fn (array $location): array => $this->fallbackItem($location['name'], $locale, $updatedAt),
            self::LOCATIONS
        );
    }

    /**
     * @return array{name:string,temp_c:string,condition:string,icon:string,updated_at:string}
     */
    private function fallbackItem(string $name, string $locale, string $updatedAt): array
    {
        return [
            'name' => $name,
            'temp_c' => '--',
            'condition' => $locale === 'en' ? 'Weather update' : 'Pembaruan cuaca',
            'icon' => 'fas fa-cloud',
            'updated_at' => $updatedAt,
        ];
    }

    /**
     * @return array{0:string,1:string}
     */
    private function mapWeather(int $code, bool $isDay, string $locale): array
    {
        $id = [
            0 => 'Cerah',
            1 => 'Cerah berawan',
            2 => 'Berawan',
            3 => 'Mendung',
            45 => 'Kabut',
            48 => 'Kabut tebal',
            51 => 'Gerimis ringan',
            53 => 'Gerimis',
            55 => 'Gerimis lebat',
            56 => 'Gerimis beku ringan',
            57 => 'Gerimis beku lebat',
            61 => 'Hujan ringan',
            63 => 'Hujan',
            65 => 'Hujan lebat',
            66 => 'Hujan beku ringan',
            67 => 'Hujan beku lebat',
            71 => 'Salju ringan',
            73 => 'Salju',
            75 => 'Salju lebat',
            77 => 'Butir salju',
            80 => 'Hujan lokal',
            81 => 'Hujan deras',
            82 => 'Hujan sangat deras',
            85 => 'Hujan salju ringan',
            86 => 'Hujan salju lebat',
            95 => 'Badai petir',
            96 => 'Badai + hujan es',
            99 => 'Badai ekstrem',
        ];

        $en = [
            0 => 'Clear',
            1 => 'Mostly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45 => 'Fog',
            48 => 'Dense fog',
            51 => 'Light drizzle',
            53 => 'Drizzle',
            55 => 'Heavy drizzle',
            56 => 'Freezing drizzle',
            57 => 'Dense freezing drizzle',
            61 => 'Light rain',
            63 => 'Rain',
            65 => 'Heavy rain',
            66 => 'Freezing rain',
            67 => 'Heavy freezing rain',
            71 => 'Light snow',
            73 => 'Snow',
            75 => 'Heavy snow',
            77 => 'Snow grains',
            80 => 'Rain showers',
            81 => 'Heavy showers',
            82 => 'Violent showers',
            85 => 'Snow showers',
            86 => 'Heavy snow showers',
            95 => 'Thunderstorm',
            96 => 'Thunder + hail',
            99 => 'Severe storm',
        ];

        $condition = $locale === 'en'
            ? ($en[$code] ?? 'Weather')
            : ($id[$code] ?? 'Cuaca');

        $icon = match (true) {
            $code === 0 && $isDay => 'fas fa-sun',
            $code === 0 && ! $isDay => 'fas fa-moon',
            in_array($code, [1, 2], true) => 'fas fa-cloud-sun',
            $code === 3 => 'fas fa-cloud',
            in_array($code, [45, 48], true) => 'fas fa-smog',
            in_array($code, [51, 53, 55, 56, 57, 61, 63, 65, 66, 67, 80, 81, 82], true) => 'fas fa-cloud-rain',
            in_array($code, [71, 73, 75, 77, 85, 86], true) => 'fas fa-snowflake',
            in_array($code, [95, 96, 99], true) => 'fas fa-bolt',
            default => 'fas fa-cloud',
        };

        return [$condition, $icon];
    }
}
