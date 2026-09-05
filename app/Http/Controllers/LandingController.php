<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\City;
use App\Models\Location;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;

class LandingController extends Controller
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
        $featuredLocations = $query->take(3)->get();

        $totalCo2Avoided = Activity::sum('co2_avoided_kg') ?? 125.4;
        $totalEcoActions = Activity::count();
        $locationCount = Location::where('is_active', true)->count();

        return view('landing', compact(
            'weather',
            'airQuality',
            'featuredLocations',
            'totalCo2Avoided',
            'totalEcoActions',
            'locationCount'
        ));
    }
}
