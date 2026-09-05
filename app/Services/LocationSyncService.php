<?php

namespace App\Services;

use App\Contracts\LocationProviderInterface;
use App\Models\City;
use App\Models\Location;
use App\Models\LocationSyncLog;
use App\Services\External\OverpassLocationProvider;
use Illuminate\Support\Str;

class LocationSyncService
{
    protected LocationProviderInterface $provider;

    public function __construct(?LocationProviderInterface $provider = null)
    {
        $this->provider = $provider ?? app(OverpassLocationProvider::class);
    }

    /**
     * Synchronize city locations using the configured location provider.
     */
    public function syncCityLocations(City $city, int $radiusMeters = 10000, ?int $existingSyncLogId = null): array
    {
        $startedAt = now();
        $providerKey = $this->provider->getProviderKey();

        if ($existingSyncLogId) {
            $syncLog = LocationSyncLog::find($existingSyncLogId);
        }

        if (empty($syncLog)) {
            $syncLog = LocationSyncLog::create([
                'city_id' => $city->id,
                'provider' => $providerKey,
                'search_radius' => $radiusMeters,
                'discovered_count' => 0,
                'created_count' => 0,
                'updated_count' => 0,
                'duplicate_count' => 0,
                'skipped_count' => 0,
                'deactivated_count' => 0,
                'status' => 'running',
                'started_at' => $startedAt,
            ]);
        } else {
            $syncLog->update([
                'status' => 'running',
                'started_at' => $startedAt,
            ]);
        }

        try {
            $discoveredLocations = $this->provider->discoverLocations($city, $radiusMeters);
            $discoveredCount = count($discoveredLocations);

            if ($discoveredCount === 0) {
                $errMsg = 'Server OpenStreetMap Overpass sedang mengalami pembatasan kuota (rate-limit / sibuk). Mohon tunggu 15-30 detik sebelum memicu sinkronisasi kembali. Data lokasi lama di database tetap aman.';

                $syncLog->update([
                    'discovered_count' => 0,
                    'status' => 'partial',
                    'error_message' => $errMsg,
                    'completed_at' => now(),
                ]);

                return [
                    'status' => 'partial',
                    'message' => $errMsg,
                    'discovered_count' => 0,
                    'created_count' => 0,
                    'updated_count' => 0,
                    'duplicate_count' => 0,
                    'skipped_count' => 0,
                    'log_id' => $syncLog->id,
                ];
            }

            $createdCount = 0;
            $updatedCount = 0;
            $duplicateCount = 0;
            $skippedCount = 0;

            foreach ($discoveredLocations as $payload) {
                $extId = $payload['external_id'];
                $name = $payload['name'];
                $lat = $payload['latitude'];
                $lng = $payload['longitude'];

                if (empty($name) || $lat === null || $lng === null) {
                    $skippedCount++;
                    continue;
                }

                // Primary match by source + external_id
                $existingLocation = Location::where('source', $providerKey)
                    ->where('external_id', $extId)
                    ->first();

                // Fallback match by city_id + name + geographic proximity (< 50 meters)
                if (!$existingLocation) {
                    $existingLocation = Location::where('city_id', $city->id)
                        ->where('name', $name)
                        ->whereBetween('latitude', [$lat - 0.0005, $lat + 0.0005])
                        ->whereBetween('longitude', [$lng - 0.0005, $lng + 0.0005])
                        ->first();
                }

                if ($existingLocation) {
                    $duplicateCount++;

                    // Upsert: Update information while preserving admin-controlled fields like is_verified
                    $existingLocation->update([
                        'city_id' => $city->id,
                        'external_id' => $extId,
                        'name' => $name,
                        'category' => $payload['category'],
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'address' => $payload['address'] ?? $existingLocation->address,
                        'website' => $payload['website'] ?? $existingLocation->website,
                        'phone' => $payload['phone'] ?? $existingLocation->phone,
                        'opening_hours' => $payload['opening_hours'] ?? $existingLocation->opening_hours,
                        'accessibility_info' => $payload['accessibility_info'] ?? $existingLocation->accessibility_info,
                        'last_seen_at' => now(),
                        'last_synced_at' => now(),
                    ]);

                    $updatedCount++;
                } else {
                    Location::create([
                        'city_id' => $city->id,
                        'external_id' => $extId,
                        'source' => $providerKey,
                        'source_id' => 'OSM-' . str_replace(['node/', 'way/', 'relation/'], '', $extId),
                        'name' => $name,
                        'slug' => Str::slug($name) . '-' . Str::random(5),
                        'category' => $payload['category'],
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'address' => $payload['address'],
                        'website' => $payload['website'],
                        'phone' => $payload['phone'],
                        'opening_hours' => $payload['opening_hours'],
                        'accessibility_info' => $payload['accessibility_info'],
                        'green_score' => $payload['green_score'] ?? 75,
                        'accessibility_score' => $payload['accessibility_score'] ?? 75,
                        'walking_score' => $payload['walking_score'] ?? 75,
                        'bike_friendly' => $payload['bike_friendly'] ?? true,
                        'is_active' => true,
                        'is_verified' => false,
                        'last_seen_at' => now(),
                        'last_synced_at' => now(),
                    ]);

                    $createdCount++;
                }
            }

            $syncLog->update([
                'discovered_count' => $discoveredCount,
                'created_count' => $createdCount,
                'updated_count' => $updatedCount,
                'duplicate_count' => $duplicateCount,
                'skipped_count' => $skippedCount,
                'status' => 'success',
                'completed_at' => now(),
            ]);

            return [
                'status' => 'success',
                'message' => "Sinkronisasi lokasi untuk {$city->name} selesai secara otomatis! Ditemukan: {$discoveredCount}, Baru: {$createdCount}, Diperbarui: {$updatedCount}, Duplikat: {$duplicateCount}.",
                'discovered_count' => $discoveredCount,
                'created_count' => $createdCount,
                'updated_count' => $updatedCount,
                'duplicate_count' => $duplicateCount,
                'skipped_count' => $skippedCount,
                'log_id' => $syncLog->id,
            ];
        } catch (\Throwable $e) {
            $syncLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            return [
                'status' => 'failed',
                'message' => "Sinkronisasi gagal. Data lokasi sebelumnya tetap digunakan.",
                'error_message' => $e->getMessage(),
                'discovered_count' => 0,
                'created_count' => 0,
                'updated_count' => 0,
                'duplicate_count' => 0,
                'skipped_count' => 0,
                'log_id' => $syncLog->id,
            ];
        }
    }
}
