<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\DataSyncLog;
use App\Models\EnvironmentalData;
use App\Models\Location;
use App\Models\User;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;

class AdminDashboardController extends Controller
{
    public function index(
        BmkgService $bmkgService,
        AirQualityService $airQualityService
    ) {
        $totalUsers = User::count();
        $totalEcoActions = Activity::count();
        $totalCo2Avoided = Activity::sum('co2_avoided_kg');
        $activeLocations = Location::where('is_active', true)->count();

        $weather = $bmkgService->getWeather();
        $airQuality = $airQualityService->getCurrentAirQuality();

        $apiStatuses = [
            'bmkg' => DataSyncLog::where('source', 'bmkg')->latest()->first(),
            'air_quality' => DataSyncLog::where('source', 'air_quality')->latest()->first(),
            'openstreetmap' => DataSyncLog::where('source', 'openstreetmap')->latest()->first(),
        ];

        $latestActivities = Activity::with(['user', 'location'])->latest()->take(5)->get();
        $syncLogs = DataSyncLog::latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalEcoActions',
            'totalCo2Avoided',
            'activeLocations',
            'weather',
            'airQuality',
            'apiStatuses',
            'latestActivities',
            'syncLogs'
        ));
    }
}
