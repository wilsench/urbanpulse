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

$optimizedQuery = <<<OVERPASS
[out:json][timeout:10];
(
  nwr["leisure"~"park|garden|nature_reserve|sports_centre|stadium|pitch|fitness_centre"](around:{$radius},{$lat},{$lng});
  nwr["amenity"~"library|community_centre|school|university"](around:{$radius},{$lat},{$lng});
  nwr["highway"="bus_stop"](around:{$radius},{$lat},{$lng});
  nwr["railway"="station"](around:{$radius},{$lat},{$lng});
);
out center 100;
OVERPASS;

echo "Testing Optimized Query on https://overpass-api.de/api/interpreter ...\n";

$start = microtime(true);
$res = Http::withHeaders([
    'User-Agent' => 'UrbanPulse/1.0 (Sustainable City Platform; +https://urbanpulse.id)',
    'Accept' => 'application/json',
])->asForm()->timeout(10)->post('https://overpass-api.de/api/interpreter', ['data' => $optimizedQuery]);

$duration = round(microtime(true) - $start, 2);

echo "STATUS: " . $res->status() . " (Time: {$duration}s)\n";
if ($res->successful()) {
    $elements = $res->json()['elements'] ?? [];
    echo "SUCCESS! Returned " . count($elements) . " elements!\n";
    if (count($elements) > 0) {
        echo "First item: " . ($elements[0]['tags']['name'] ?? 'No name') . " (" . ($elements[0]['tags']['leisure'] ?? $elements[0]['tags']['amenity'] ?? 'other') . ")\n";
    }
} else {
    echo "ERR BODY: " . substr($res->body(), 0, 200) . "\n";
}
