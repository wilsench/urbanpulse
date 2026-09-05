<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Models\LocationSyncLog;
use App\Services\LocationSyncService;

$city = City::first();
$logs = LocationSyncLog::where('city_id', $city->id)->latest()->take(5)->get();

echo "=== LATEST LOCATION SYNC LOGS FOR {$city->name} ===\n\n";
foreach ($logs as $log) {
    echo "Log ID: {$log->id} | Status: {$log->status} | Radius: {$log->search_radius}m\n";
    echo "Discovered: {$log->discovered_count} | Created: {$log->created_count} | Updated: {$log->updated_count} | Duplicates: {$log->duplicate_count}\n";
    echo "Message: {$log->error_message}\n";
    echo "Started: {$log->started_at} | Completed: {$log->completed_at}\n";
    echo "--------------------------------------------------\n";
}
