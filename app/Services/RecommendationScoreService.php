<?php

namespace App\Services;

use App\Models\Location;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;

class RecommendationScoreService
{
    protected CrowdEstimationService $crowdService;
    protected AirQualityService $airQualityService;
    protected BmkgService $bmkgService;

    public function __construct(
        CrowdEstimationService $crowdService,
        AirQualityService $airQualityService,
        BmkgService $bmkgService
    ) {
        $this->crowdService = $crowdService;
        $this->airQualityService = $airQualityService;
        $this->bmkgService = $bmkgService;
    }

    /**
     * Calculate recommendation match score (0-100%) for a location based on user criteria.
     */
    public function calculateScore(
        Location $location,
        string $activityType = 'exercise',
        string $preferredCrowd = 'low',
        string $preferredTime = 'afternoon',
        string $transportMode = 'bicycle',
        ?array $weather = null,
        ?array $airQuality = null
    ): array {
        // Fetch current environmental baseline ONCE if not passed
        if ($weather === null) {
            $weather = $this->bmkgService->getWeather($location->latitude, $location->longitude);
        }
        if ($airQuality === null) {
            $airQuality = $this->airQualityService->getCurrentAirQuality($location->latitude, $location->longitude);
        }

        $crowd = $this->crowdService->estimateCrowd($location);

        // 1. Air Quality Score (Max 30 pts)
        $aqi = $airQuality['air_quality_index'] ?? 50;
        $aqiRawScore = max(0, min(100, 100 - ($aqi * 0.6))); // AQI 0 = 100%, AQI 100 = 40%
        $aqiPoints = round(($aqiRawScore / 100) * 30, 1);

        // 2. Crowd Level Score (Max 25 pts)
        $crowdLevel = $crowd['level'];
        $crowdRawScore = match ($preferredCrowd) {
            'low' => ($crowdLevel === 'LOW' ? 100 : ($crowdLevel === 'MEDIUM' ? 65 : 30)),
            'medium' => ($crowdLevel === 'MEDIUM' ? 100 : ($crowdLevel === 'LOW' ? 80 : 50)),
            default => 85,
        };
        $crowdPoints = round(($crowdRawScore / 100) * 25, 1);

        // 3. Green Score (Max 20 pts)
        $greenPoints = round(($location->green_score / 100) * 20, 1);

        // 4. Accessibility Score (Max 15 pts)
        $accessScore = $location->accessibility_score;
        if ($transportMode === 'bicycle' && $location->bike_friendly) {
            $accessScore = min(100, $accessScore + 15);
        } elseif ($transportMode === 'walking') {
            $accessScore = min(100, ($accessScore + $location->walking_score) / 2);
        }
        $accessPoints = round(($accessScore / 100) * 15, 1);

        // 5. Weather Score (Max 10 pts)
        $rainProb = $weather['rain_probability'] ?? 20;
        $weatherRawScore = max(10, 100 - $rainProb);
        $weatherPoints = round(($weatherRawScore / 100) * 10, 1);

        $totalScore = round($aqiPoints + $crowdPoints + $greenPoints + $accessPoints + $weatherPoints);

        // Formulate transparent reasons
        $reasons = [];
        if ($aqiPoints >= 24) {
            $reasons[] = "Kualitas udara sangat mendukung ({$airQuality['air_quality_status']}, AQI: {$aqi})";
        }
        if ($crowdLevel === strtoupper($preferredCrowd) || ($preferredCrowd === 'low' && $crowdLevel === 'LOW')) {
            $reasons[] = "Tingkat keramaian sesuai preferensi ({$crowd['label']})";
        }
        if ($location->green_score >= 85) {
            $reasons[] = "Ruang terbuka hijau tinggi (Green Score: {$location->green_score}/100)";
        }
        if ($transportMode === 'bicycle' && $location->bike_friendly) {
            $reasons[] = "Akses sangat ramah sepeda dengan jalur aman";
        }
        if ($rainProb <= 30) {
            $reasons[] = "Peluang hujan rendah ({$rainProb}%)";
        }

        return [
            'total_score' => $totalScore, // match percentage
            'breakdown' => [
                'air_quality' => [
                    'earned' => $aqiPoints,
                    'max' => 30,
                    'value' => "AQI {$aqi} ({$airQuality['air_quality_status']})",
                    'source' => $airQuality['source'],
                ],
                'crowd_level' => [
                    'earned' => $crowdPoints,
                    'max' => 25,
                    'value' => $crowd['label'],
                    'source' => $crowd['source'],
                ],
                'green_space' => [
                    'earned' => $greenPoints,
                    'max' => 20,
                    'value' => "{$location->green_score}/100",
                    'source' => 'UrbanPulse Location Index',
                ],
                'accessibility' => [
                    'earned' => $accessPoints,
                    'max' => 15,
                    'value' => "{$location->accessibility_score}/100",
                    'source' => 'UrbanPulse Mobility Index',
                ],
                'weather' => [
                    'earned' => $weatherPoints,
                    'max' => 10,
                    'value' => "{$weather['weather_description']}, Hujan {$rainProb}%",
                    'source' => $weather['source'],
                ],
            ],
            'reasons' => $reasons,
            'recommended_time_slot' => match ($preferredTime) {
                'morning' => '06:00 - 09:00 WIB',
                'afternoon' => '16:00 - 18:00 WIB',
                'evening' => '18:30 - 20:30 WIB',
                default => '15:30 - 18:00 WIB',
            },
        ];
    }
}
