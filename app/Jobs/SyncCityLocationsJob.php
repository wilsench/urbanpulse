<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\LocationSyncLog;
use App\Services\LocationSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncCityLocationsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public City $city;
    public int $radiusMeters;
    public ?int $syncLogId;

    /**
     * Create a new job instance.
     */
    public function __construct(City $city, int $radiusMeters = 10000, ?int $syncLogId = null)
    {
        $this->city = $city;
        $this->radiusMeters = $radiusMeters;
        $this->syncLogId = $syncLogId;
    }

    /**
     * Execute the job.
     */
    public function handle(LocationSyncService $syncService): void
    {
        $syncService->syncCityLocations($this->city, $this->radiusMeters, $this->syncLogId);
    }
}
