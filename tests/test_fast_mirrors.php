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

$query = <<<OVERPASS
[out:json][timeout:15];
(
  nwr["leisure"="park"](around:{$radius},{$lat},{$lng});
  nwr["leisure"="sports_centre"](around:{$radius},{$lat},{$lng});
  nwr["amenity"="library"](around:{$radius},{$lat},{$lng});
);
out center 50;
OVERPASS;

$endpoints = [
    'https://lz4.overpass-api.de/api/interpreter',
    'https://z.overpass-api.de/api/interpreter',
    'https://overpass.kumi.systems/api/interpreter',
    'https://overpass.nchc.org.tw/api/interpreter',
    'https://overpass-api.de/api/interpreter',
];

foreach ($endpoints as $url) {
    echo "Testing mirror: {$url}\n";
    $start = microtime(true);
    try {
        $res = Http::withHeaders([
            'User-Agent' => 'UrbanPulse/1.0 (Sustainable City Platform; +https://urbanpulse.id)',
            'Accept' => 'application/json',
        ])->asForm()->timeout(10)->post($url, ['data' => $query]);

        $duration = round(microtime(true) - $start, 2);
        echo "STATUS: " . $res->status() . " ({$duration}s)\n";

        if ($res->successful()) {
            $elements = $res->json()['elements'] ?? [];
            echo "SUCCESS! Elements: " . count($elements) . "\n";
        } else {
            echo "ERR: " . substr($res->body(), 0, 100) . "\n";
        }
    } catch (\Throwable $e) {
        echo "FAIL: " . $e->getMessage() . "\n";
    }
    echo "----------------------------------------\n";
}
