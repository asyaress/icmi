<?php

namespace App\Http\Controllers;

use App\Support\Weather\KaltimWeatherService;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    public function __invoke(KaltimWeatherService $weatherService): JsonResponse
    {
        $items = $weatherService->getItems(app()->getLocale());

        return response()->json([
            'items' => $items,
            'fetched_at' => now('Asia/Makassar')->toIso8601String(),
            'source' => 'open-meteo.com',
        ]);
    }
}
