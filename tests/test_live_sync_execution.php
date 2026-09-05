<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Services\LocationSyncService;

$cities = City::all();
$syncService = app(LocationSyncService::class);

echo "=== STARTING LIVE OPENSTREETMAP LOCATION SYNCHRONIZATION TEST ===\n\n";

foreach ($cities as $city) {
    echo "Syncing City: {$city->name} (Lat: {$city->latitude}, Lng: {$city->longitude})...\n";
    $result = $syncService->syncCityLocations($city, 10000);
    echo "STATUS: " . $result['status'] . "\n";
    echo "Discovered: " . $result['discovered_count'] . "\n";
    echo "Created: " . $result['created_count'] . "\n";
    echo "Updated: " . $result['updated_count'] . "\n";
    echo "Duplicate: " . $result['duplicate_count'] . "\n";
    echo "Skipped: " . $result['skipped_count'] . "\n";
    echo "Message: " . $result['message'] . "\n";
    echo "--------------------------------------------------\n";
}

echo "=== ALL LIVE CITY SYNCS COMPLETED SUCCESSFULLY ===\n";
