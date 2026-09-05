<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;
use App\Services\CrowdEstimationService;
use App\Services\RecommendationScoreService;

class LocationController extends Controller
{
    public function show(
        Location $location,
        BmkgService $bmkgService,
        AirQualityService $airQualityService,
        CrowdEstimationService $crowdService,
        RecommendationScoreService $scoreService
    ) {
        $weather = $bmkgService->getWeather($location->latitude, $location->longitude);
        $airQuality = $airQualityService->getCurrentAirQuality($location->latitude, $location->longitude);
        $crowd = $crowdService->estimateCrowd($location);
        $score = $scoreService->calculateScore($location);

        return view('locations.show', compact(
            'location',
            'weather',
            'airQuality',
            'crowd',
            'score'
        ));
    }
}
