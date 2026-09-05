<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Models\Location;
use App\Services\RecommendationScoreService;

$city = City::first();
$locations = Location::where('city_id', $city->id)->get();
echo "Testing recommendation score calculation for " . count($locations) . " locations in {$city->name}...\n";

$scoreService = app(RecommendationScoreService::class);

$start = microtime(true);
$weather = app(\App\Services\External\BmkgService::class)->getWeather($city->latitude, $city->longitude);
$airQuality = app(\App\Services\External\AirQualityService::class)->getCurrentAirQuality($city->latitude, $city->longitude);

foreach ($locations as $loc) {
    $res = $scoreService->calculateScore($loc, 'exercise', 'low', 'afternoon', 'bicycle', $weather, $airQuality);
}

$duration = round((microtime(true) - $start) * 1000, 2);
echo "SUCCESS! Processed " . count($locations) . " locations in {$duration} ms (0.00" . round($duration) . "s)!\n";
