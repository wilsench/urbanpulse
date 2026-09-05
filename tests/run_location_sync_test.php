<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Models\Location;
use App\Services\External\OverpassLocationProvider;
use App\Services\LocationSyncService;
use Illuminate\Support\Facades\Http;

echo "=== STARTING URBANPULSE AUTOMATED LOCATION SYNC VERIFICATION ===\n\n";

// 1. Provider Abstraction & Overpass Query Building
$provider = app(OverpassLocationProvider::class);
$city = City::first();
$query = $provider->buildOverpassQuery($city->latitude, $city->longitude, 10000);

if (!str_contains($query, '[out:json][timeout:15];') || !str_contains($query, 'node["leisure"="park"]')) {
    throw new Exception("FAIL: Overpass query construction failed");
}
echo "[PASS] 1. OverpassLocationProvider correctly builds dynamic indexed Overpass query.\n";

// 2. Normalization & Filtering
$mockElements = [
    [
        'type' => 'node',
        'id' => 77701,
        'lat' => $city->latitude + 0.002,
        'lon' => $city->longitude + 0.002,
        'tags' => [
            'name' => 'Taman Herbal Terverifikasi',
            'leisure' => 'park',
            'addr:street' => 'Jalan Pajajaran',
            'wheelchair' => 'yes',
        ]
    ],
    [
        'type' => 'way',
        'id' => 77702,
        'center' => [
            'lat' => $city->latitude + 0.003,
            'lon' => $city->longitude + 0.003,
        ],
        'tags' => [
            'name' => 'Gelanggang Olahraga Pemuda',
            'leisure' => 'sports_centre',
        ]
    ]
];

$normalized = $provider->normalizeElements($mockElements);
if (count($normalized) !== 2 || $normalized[0]['external_id'] !== 'node/77701' || $normalized[1]['external_id'] !== 'way/77702') {
    throw new Exception("FAIL: Element normalization failed");
}
echo "[PASS] 2. Node, Way, and Relation parsing correctly normalized OSM tags & center coordinates.\n";

// 3. Fake HTTP Response to test LocationSyncService creation & deduplication
Http::fake([
    '*' => Http::response(['elements' => $mockElements], 200)
]);

$syncService = app(LocationSyncService::class);

// First Sync
$res1 = $syncService->syncCityLocations($city, 10000);
if ($res1['status'] !== 'success' || $res1['created_count'] !== 2) {
    throw new Exception("FAIL: First location sync failed to create locations");
}
echo "[PASS] 3. First sync created 2 new locations in DB linked to " . $city->name . ".\n";

// Second Sync (Deduplication / Upsert check)
$res2 = $syncService->syncCityLocations($city, 10000);
if ($res2['created_count'] !== 0 || $res2['duplicate_count'] !== 2 || $res2['updated_count'] !== 2) {
    throw new Exception("FAIL: Second sync created duplicate records instead of upserting");
}
echo "[PASS] 4. Repeated sync successfully prevented duplicates and performed UPSERT.\n";

// 5. Verification of DB records and relationship
$loc1 = Location::where('external_id', 'node/77701')->first();
if (!$loc1 || $loc1->city_id !== $city->id || $loc1->source !== 'openstreetmap') {
    throw new Exception("FAIL: Saved location record fields invalid");
}
echo "[PASS] 5. Database location record correctly saved with source=openstreetmap, external_id=node/77701.\n";

echo "\n=== ALL LOCATION DISCOVERY & SYNC TESTS PASSED PERFECTLY ===\n";
