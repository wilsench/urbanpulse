<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;
use App\Services\RecommendationScoreService;

class MapController extends Controller
{
    public function index(
        BmkgService $bmkgService,
        AirQualityService $airQualityService,
        RecommendationScoreService $scoreService
    ) {
        $activeCity = view()->shared('activeCity');
        $lat = $activeCity ? $activeCity->latitude : -6.5971;
        $lng = $activeCity ? $activeCity->longitude : 106.7949;
        $cityId = $activeCity ? $activeCity->id : null;

        $query = Location::where('is_active', true);
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        $locations = $query->get();

        $weather = $bmkgService->getWeather($lat, $lng);
        $airQuality = $airQualityService->getCurrentAirQuality($lat, $lng);

        $formattedLocations = $locations->map(function ($loc) use ($scoreService, $weather, $airQuality) {
            $score = $scoreService->calculateScore($loc);
            return [
                'id' => $loc->id,
                'name' => $loc->name,
                'slug' => $loc->slug,
                'category' => $loc->category,
                'latitude' => (float) $loc->latitude,
                'longitude' => (float) $loc->longitude,
                'address' => $loc->address,
                'green_score' => $loc->green_score,
                'accessibility_score' => $loc->accessibility_score,
                'walking_score' => $loc->walking_score,
                'bike_friendly' => $loc->bike_friendly,
                'source' => $loc->source,
                'source_id' => $loc->source_id,
                'match_score' => $score['total_score'],
                'weather_desc' => $weather['weather_description'],
                'temperature' => $weather['temperature'],
                'aqi_status' => $airQuality['air_quality_status'],
                'aqi_val' => $airQuality['air_quality_index'],
            ];
        });

        return view('map', [
            'locationsJson' => json_encode($formattedLocations),
            'weather' => $weather,
            'airQuality' => $airQuality,
        ]);
    }
}
