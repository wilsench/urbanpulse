<?php

namespace App\Http\Controllers;

use App\Models\DataSyncLog;
use App\Models\Location;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;

class CityDashboardController extends Controller
{
    public function index(
        BmkgService $bmkgService,
        AirQualityService $airQualityService
    ) {
        $activeCity = view()->shared('activeCity');
        $lat = $activeCity ? $activeCity->latitude : -6.5971;
        $lng = $activeCity ? $activeCity->longitude : 106.7949;
        $cityId = $activeCity ? $activeCity->id : null;

        $weather = $bmkgService->getWeather($lat, $lng);
        $airQuality = $airQualityService->getCurrentAirQuality($lat, $lng);

        $query = Location::where('is_active', true);
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        $locations = $query->get();

        $avgGreenScore = round($locations->avg('green_score') ?? 85);
        $avgAccessibility = round($locations->avg('accessibility_score') ?? 88);

        // Sustainability score logic: 40% AQI, 30% Green Space, 20% Accessibility, 10% Weather
        $aqiScore = max(0, min(100, 100 - (($airQuality['air_quality_index'] ?? 45) * 0.6)));
        $sustainabilityScore = round(
            ($aqiScore * 0.4) +
            ($avgGreenScore * 0.3) +
            ($avgAccessibility * 0.2) +
            ((100 - ($weather['rain_probability'] ?? 20)) * 0.1)
        );

        $systemStatuses = [
            'weather' => [
                'name' => 'BMKG Meteorological API',
                'status' => 'ONLINE',
                'source' => $weather['source'],
                'last_updated' => $weather['recorded_at'],
                'is_cached' => $weather['is_cached'],
            ],
            'air_quality' => [
                'name' => 'Air Quality Open Data API',
                'status' => 'ONLINE',
                'source' => $airQuality['source'],
                'last_updated' => $airQuality['recorded_at'],
                'is_cached' => $airQuality['is_cached'],
            ],
            'map_data' => [
                'name' => 'OpenStreetMap Overpass Service',
                'status' => 'ONLINE',
                'source' => 'OpenStreetMap Contributors',
                'last_updated' => now()->toDateTimeString(),
                'is_cached' => false,
            ],
            'ai_assistant' => [
                'name' => 'UrbanPulse AI Engine',
                'status' => 'ONLINE',
                'source' => 'Gemini 1.5 Flash + UrbanPulse Knowledge Base',
                'last_updated' => now()->toDateTimeString(),
                'is_cached' => false,
            ],
        ];

        $latestLogs = DataSyncLog::latest()->take(5)->get();

        return view('city.dashboard', compact(
            'weather',
            'airQuality',
            'sustainabilityScore',
            'avgGreenScore',
            'avgAccessibility',
            'systemStatuses',
            'latestLogs',
            'locations'
        ));
    }
}
