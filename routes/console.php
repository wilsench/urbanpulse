<?php

use App\Models\EnvironmentalData;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Environmental Data Sync Every Hour
Schedule::call(function (BmkgService $bmkgService, AirQualityService $airQualityService) {
    $weather = $bmkgService->getWeather();
    $airQuality = $airQualityService->getCurrentAirQuality();

    EnvironmentalData::create([
        'city' => 'Bogor',
        'latitude' => -6.5971,
        'longitude' => 106.7949,
        'temperature' => $weather['temperature'],
        'humidity' => $weather['humidity'],
        'weather_code' => $weather['weather_code'],
        'weather_description' => $weather['weather_description'],
        'rainfall' => $weather['rainfall'],
        'rain_probability' => $weather['rain_probability'],
        'air_quality_index' => $airQuality['air_quality_index'],
        'pm25' => $airQuality['pm25'],
        'air_quality_status' => $airQuality['air_quality_status'],
        'recorded_at' => now(),
        'source' => $weather['source'] . ' & ' . $airQuality['source'],
        'is_cached' => false,
    ]);
})->hourly()->name('sync-environmental-data');
