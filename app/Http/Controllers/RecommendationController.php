<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\RecommendationLog;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;
use App\Services\RecommendationScoreService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected RecommendationScoreService $scoreService;
    protected BmkgService $bmkgService;
    protected AirQualityService $airQualityService;

    public function __construct(
        RecommendationScoreService $scoreService,
        BmkgService $bmkgService,
        AirQualityService $airQualityService
    ) {
        $this->scoreService = $scoreService;
        $this->bmkgService = $bmkgService;
        $this->airQualityService = $airQualityService;
    }

    public function index()
    {
        return view('recommend.index');
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'activity_type' => ['required', 'string'],
            'preferred_crowd' => ['required', 'string'],
            'preferred_time' => ['required', 'string'],
            'transport_mode' => ['required', 'string'],
        ]);

        $activeCity = view()->shared('activeCity');
        $cityId = $activeCity ? $activeCity->id : null;
        $lat = $activeCity ? $activeCity->latitude : -6.5971;
        $lng = $activeCity ? $activeCity->longitude : 106.7949;

        // Pre-fetch environmental baseline ONCE for the active city (0.001s instead of calling 140 times)
        $weather = $this->bmkgService->getWeather($lat, $lng);
        $airQuality = $this->airQualityService->getCurrentAirQuality($lat, $lng);

        $query = Location::where('is_active', true);
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        $locations = $query->get();

        $scoredLocations = [];

        foreach ($locations as $loc) {
            $analysis = $this->scoreService->calculateScore(
                $loc,
                $validated['activity_type'],
                $validated['preferred_crowd'],
                $validated['preferred_time'],
                $validated['transport_mode'],
                $weather,
                $airQuality
            );

            $scoredLocations[] = [
                'location' => $loc,
                'total_score' => $analysis['total_score'],
                'breakdown' => $analysis['breakdown'],
                'reasons' => $analysis['reasons'],
                'recommended_time_slot' => $analysis['recommended_time_slot'],
            ];
        }

        // Sort descending by total match score
        usort($scoredLocations, fn($a, $b) => $b['total_score'] <=> $a['total_score']);

        $top3 = array_slice($scoredLocations, 0, 3);

        // Log recommendation query
        RecommendationLog::create([
            'user_id' => auth()->id(),
            'activity_type' => $validated['activity_type'],
            'preferred_crowd' => $validated['preferred_crowd'],
            'preferred_time' => $validated['preferred_time'],
            'transport_mode' => $validated['transport_mode'],
            'recommendations_json' => array_map(fn($item) => [
                'location_id' => $item['location']->id,
                'name' => $item['location']->name,
                'score' => $item['total_score'],
            ], $top3),
        ]);

        return view('recommend.results', [
            'criteria' => $validated,
            'results' => $top3,
        ]);
    }
}
