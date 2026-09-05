<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Location;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;

class UserDashboardController extends Controller
{
    public function index(
        BmkgService $bmkgService,
        AirQualityService $airQualityService
    ) {
        $user = auth()->user();
        $recentActions = Activity::where('user_id', $user->id)->with('location')->latest()->take(5)->get();

        $totalCo2 = Activity::where('user_id', $user->id)->sum('co2_avoided_kg');
        $totalActionsCount = Activity::where('user_id', $user->id)->count();

        $weather = $bmkgService->getWeather();
        $airQuality = $airQualityService->getCurrentAirQuality();
        $recommendedLocations = Location::where('is_active', true)->take(3)->get();

        return view('user.dashboard', compact(
            'user',
            'recentActions',
            'totalCo2',
            'totalActionsCount',
            'weather',
            'airQuality',
            'recommendedLocations'
        ));
    }
}
