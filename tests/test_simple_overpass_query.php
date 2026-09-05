<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use Illuminate\Support\Facades\Http;

$city = City::first();
$lat = $city->latitude;
$lng = $city->longitude;
$radius = 10000;

$simpleQuery = <<<OVERPASS
[out:json][timeout:15];
(
  node["leisure"="park"](around:{$radius},{$lat},{$lng});
  way["leisure"="park"](around:{$radius},{$lat},{$lng});
  node["leisure"="garden"](around:{$radius},{$lat},{$lng});
  way["leisure"="garden"](around:{$radius},{$lat},{$lng});
  node["leisure"="sports_centre"](around:{$radius},{$lat},{$lng});
  way["leisure"="sports_centre"](around:{$radius},{$lat},{$lng});
  node["amenity"="library"](around:{$radius},{$lat},{$lng});
  way["amenity"="library"](around:{$radius},{$lat},{$lng});
  node["amenity"="community_centre"](around:{$radius},{$lat},{$lng});
  way["amenity"="community_centre"](around:{$radius},{$lat},{$lng});
  node["highway"="bus_stop"](around:{$radius},{$lat},{$lng});
  node["railway"="station"](around:{$radius},{$lat},{$lng});
);
out center 150;
OVERPASS;

$endpoints = [
    'https://overpass-api.de/api/interpreter',
    'https://lz4.overpass-api.de/api/interpreter',
    'https://z.overpass-api.de/api/interpreter',
];

foreach ($endpoints as $url) {
    echo "Testing Simple Direct Query on: {$url}\n";
    $start = microtime(true);
    try {
        $res = Http::withHeaders([
            'User-Agent' => 'UrbanPulse/1.0 (Sustainable City Platform; +https://urbanpulse.id)',
            'Accept' => 'application/json',
        ])->asForm()->timeout(12)->post($url, ['data' => $simpleQuery]);

        $duration = round(microtime(true) - $start, 2);
        echo "STATUS: " . $res->status() . " (Time: {$duration}s)\n";

        if ($res->successful()) {
            $elements = $res->json()['elements'] ?? [];
            echo "SUCCESS! Returned " . count($elements) . " elements!\n";
            break;
        } else {
            echo "ERR: " . substr($res->body(), 0, 100) . "\n";
        }
    } catch (\Throwable $e) {
        echo "FAIL: " . $e->getMessage() . "\n";
    }
    echo "----------------------------------------\n";
}
