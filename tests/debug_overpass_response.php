<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Services\External\OverpassLocationProvider;
use Illuminate\Support\Facades\Http;

$city = City::first();
echo "CITY: {$city->name} (Lat: {$city->latitude}, Lng: {$city->longitude})\n";

$provider = app(OverpassLocationProvider::class);
$query = $provider->buildOverpassQuery($city->latitude, $city->longitude, 10000);

echo "QUERY:\n" . $query . "\n\n";

$endpoints = [
    'https://lz4.overpass-api.de/api/interpreter',
    'https://z.overpass-api.de/api/interpreter',
    'https://overpass-api.de/api/interpreter',
];

foreach ($endpoints as $idx => $url) {
    echo "ENDPOINT #{$idx}: {$url}\n";
    $res = Http::withHeaders([
        'User-Agent' => 'UrbanPulse/1.0 (Sustainable City Platform; +https://urbanpulse.id)',
        'Accept' => 'application/json',
    ])->asForm()->timeout(15)->post($url, ['data' => $query]);

    echo "STATUS: " . $res->status() . "\n";
    if ($res->successful()) {
        $data = $res->json();
        $elements = $data['elements'] ?? [];
        echo "RAW ELEMENTS: " . count($elements) . "\n";
        $normalized = $provider->normalizeElements($elements);
        echo "NORMALIZED LOCATIONS (WITH NAMES): " . count($normalized) . "\n";
        if (count($normalized) > 0) {
            echo "FIRST 3 LOCATIONS:\n";
            for ($i = 0; $i < min(3, count($normalized)); $i++) {
                echo "- " . $normalized[$i]['name'] . " (" . $normalized[$i]['category'] . ")\n";
            }
        }
    } else {
        echo "BODY (FIRST 200 CHARS): " . substr($res->body(), 0, 200) . "\n";
    }
    echo "========================================\n";
}
