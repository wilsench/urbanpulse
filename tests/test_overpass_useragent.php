<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Services\External\OverpassLocationProvider;
use Illuminate\Support\Facades\Http;

$city = City::first();
$provider = app(OverpassLocationProvider::class);
$query = $provider->buildOverpassQuery($city->latitude, $city->longitude, 10000);

$endpoints = [
    'https://overpass-api.de/api/interpreter',
    'https://lz4.overpass-api.de/api/interpreter',
    'https://z.overpass-api.de/api/interpreter',
];

foreach ($endpoints as $url) {
    echo "Testing: {$url}\n";

    // Method A: asForm with User-Agent
    $resA = Http::withHeaders([
        'User-Agent' => 'UrbanPulse/1.0 (Sustainable City Platform; +https://urbanpulse.id)',
        'Accept' => 'application/json',
    ])->asForm()->timeout(15)->post($url, ['data' => $query]);

    echo "Method A STATUS: " . $resA->status() . "\n";
    if ($resA->successful()) {
        $elements = $resA->json()['elements'] ?? [];
        echo "Method A ELEMENTS RETURNED: " . count($elements) . "\n";
    } else {
        echo "Method A ERR: " . substr($resA->body(), 0, 150) . "\n";
    }

    // Method B: raw body plain query text
    $resB = Http::withHeaders([
        'User-Agent' => 'UrbanPulse/1.0 (Sustainable City Platform; +https://urbanpulse.id)',
        'Accept' => 'application/json',
        'Content-Type' => 'text/plain',
    ])->timeout(15)->withBody($query, 'text/plain')->post($url);

    echo "Method B STATUS: " . $resB->status() . "\n";
    if ($resB->successful()) {
        $elements = $resB->json()['elements'] ?? [];
        echo "Method B ELEMENTS RETURNED: " . count($elements) . "\n";
    }
    echo "----------------------------------------\n";
}
