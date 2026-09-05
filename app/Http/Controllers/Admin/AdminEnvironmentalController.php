<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSyncLog;
use App\Models\EnvironmentalData;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;

class AdminEnvironmentalController extends Controller
{
    public function index()
    {
        $environmentalData = EnvironmentalData::with(['city', 'location'])->latest()->paginate(15);
        $syncLogs = DataSyncLog::latest()->paginate(10);

        return view('admin.environment.index', compact('environmentalData', 'syncLogs'));
    }

    public function syncNow(
        BmkgService $bmkgService,
        AirQualityService $airQualityService
    ) {
        $activeCity = view()->shared('activeCity');
        $cityName = $activeCity ? $activeCity->name : 'Kota Bogor';
        $cityId = $activeCity ? $activeCity->id : null;
        $lat = $activeCity ? $activeCity->latitude : -6.5971;
        $lng = $activeCity ? $activeCity->longitude : 106.7949;

        // Clear caches and re-fetch fresh data
        cache()->forget("bmkg_weather_{$lat}_{$lng}");
        cache()->forget("air_quality_{$lat}_{$lng}");

        $weather = $bmkgService->getWeather($lat, $lng);
        $airQuality = $airQualityService->getCurrentAirQuality($lat, $lng);

        EnvironmentalData::create([
            'city_id' => $cityId,
            'city' => $cityName,
            'latitude' => $lat,
            'longitude' => $lng,
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

        return redirect()->route('admin.environment.index')->with('success', "Sinkronisasi data lingkungan manual untuk {$cityName} berhasil dipicu!");
    }
}
