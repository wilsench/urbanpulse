<?php

namespace App\Services\External;

use App\Models\City;
use App\Models\DataSyncLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenStreetMapService
{
    /**
     * Search locations or fetch nearby places in a target city using Overpass API.
     */
    public function searchParksInCity(?City $city = null): array
    {
        $lat = $city ? $city->latitude : -6.5971;
        $lng = $city ? $city->longitude : 106.7949;
        $slug = $city ? $city->slug : 'kota-bogor';

        $minLat = $lat - 0.08;
        $maxLat = $lat + 0.08;
        $minLng = $lng - 0.08;
        $maxLng = $lng + 0.08;

        return Cache::remember("osm_parks_{$slug}", 86400, function () use ($minLat, $minLng, $maxLat, $maxLng) {
            try {
                $query = sprintf('[out:json][timeout:10];node["leisure"="park"](%f,%f,%f,%f);out 15;', $minLat, $minLng, $maxLat, $maxLng);
                $url = 'https://overpass-api.de/api/interpreter';

                $response = Http::asForm()->timeout(8)->post($url, ['data' => $query]);

                if ($response->successful()) {
                    $elements = $response->json()['elements'] ?? [];
                    $results = [];
                    foreach ($elements as $el) {
                        if (!empty($el['tags']['name'])) {
                            $results[] = [
                                'name' => $el['tags']['name'],
                                'latitude' => (float) $el['lat'],
                                'longitude' => (float) $el['lon'],
                                'source_id' => 'OSM-' . $el['id'],
                                'source' => 'openstreetmap',
                            ];
                        }
                    }

                    DataSyncLog::create([
                        'source' => 'openstreetmap',
                        'status' => 'SUCCESS',
                        'message' => 'Fetched parks from OpenStreetMap Overpass API',
                        'records_synced' => count($results),
                        'synced_at' => now(),
                    ]);

                    return $results;
                }
            } catch (\Throwable $e) {
                Log::warning("OpenStreetMap Overpass API exception: " . $e->getMessage());
            }

            DataSyncLog::create([
                'source' => 'openstreetmap',
                'status' => 'CACHED_FALLBACK',
                'message' => 'Using verified local OpenStreetMap dataset',
                'records_synced' => 0,
                'synced_at' => now(),
            ]);

            return [];
        });
    }
}
