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

echo "OVERPASS QUERY:\n" . $query . "\n\n";

$endpoints = [
    'https://overpass-api.de/api/interpreter',
    'https://overpass.kumi.systems/api/interpreter',
    'https://overpass.private.coffee/api/interpreter',
];

foreach ($endpoints as $url) {
    echo "Testing endpoint: {$url}\n";
    try {
        $res = Http::asForm()->timeout(15)->post($url, ['data' => $query]);
        echo "STATUS: " . $res->status() . "\n";
        if ($res->successful()) {
            $json = $res->json();
            $elements = $json['elements'] ?? [];
            echo "ELEMENTS RETURNED: " . count($elements) . "\n";
            if (count($elements) > 0) {
                echo "SAMPLE ITEM: " . json_encode($elements[0]['tags'] ?? [], JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "BODY PREVIEW: " . substr($res->body(), 0, 200) . "\n";
        }
    } catch (\Throwable $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
    }
    echo "----------------------------------------\n";
}
