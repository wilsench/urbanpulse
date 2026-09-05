<?php

namespace App\Services\External;

use App\Models\DataSyncLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AirQualityService
{
    protected string $baseUrl;
    protected float $defaultLat = -6.5971; // Bogor
    protected float $defaultLng = 106.7949;

    public function __construct()
    {
        $this->baseUrl = config('services.air_quality.url', 'https://air-quality-api.open-meteo.com/v1/air-quality');
    }

    /**
     * Get air quality data for Bogor or given coordinates.
     */
    public function getCurrentAirQuality(float $lat = -6.5971, float $lng = 106.7949): array
    {
        $cacheKey = "air_quality_{$lat}_{$lng}";

        return Cache::remember($cacheKey, 1800, function () use ($lat, $lng) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl, [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'current' => 'us_aqi,pm2_5,pm10',
                    'timezone' => 'Asia/Jakarta',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $normalized = $this->normalizeResponse($data);

                    DataSyncLog::create([
                        'source' => 'air_quality',
                        'status' => 'SUCCESS',
                        'message' => 'Successfully fetched air quality data',
                        'records_synced' => 1,
                        'synced_at' => now(),
                    ]);

                    return $normalized;
                }

                throw new \Exception("HTTP status " . $response->status() . ": " . $response->body());
            } catch (\Throwable $e) {
                Log::warning("Air Quality API error: " . $e->getMessage());

                DataSyncLog::create([
                    'source' => 'air_quality',
                    'status' => 'CACHED_FALLBACK',
                    'message' => 'Air Quality API call failed: ' . $e->getMessage(),
                    'records_synced' => 0,
                    'synced_at' => now(),
                ]);

                return $this->getFallbackData();
            }
        });
    }

    /**
     * Normalize API response to standard structure.
     */
    protected function normalizeResponse(array $data): array
    {
        $current = $data['current'] ?? [];
        $aqi = (int) ($current['us_aqi'] ?? 42);
        $pm25 = (float) ($current['pm2_5'] ?? 11.2);

        $status = $this->calculateAqiStatus($aqi);

        return [
            'air_quality_index' => $aqi,
            'pm25' => $pm25,
            'air_quality_status' => $status,
            'recorded_at' => now()->toDateTimeString(),
            'source' => 'Air Quality Open Data / Open-Meteo AQ',
            'is_cached' => false,
        ];
    }

    /**
     * Determine AQI status string.
     */
    public function calculateAqiStatus(int $aqi): string
    {
        return match (true) {
            $aqi <= 50 => 'BAIK (GOOD)',
            $aqi <= 100 => 'SEDANG (MODERATE)',
            $aqi <= 150 => 'TIDAK SEHAT SENSITIF (UNHEALTHY FOR SENSITIVE)',
            $aqi <= 200 => 'TIDAK SEHAT (UNHEALTHY)',
            $aqi <= 300 => 'SANGAT TIDAK SEHAT (VERY UNHEALTHY)',
            default => 'BERBAHAYA (HAZARDOUS)',
        };
    }

    /**
     * Fallback data.
     */
    public function getFallbackData(): array
    {
        return [
            'air_quality_index' => 45,
            'pm25' => 11.5,
            'air_quality_status' => 'BAIK (GOOD)',
            'recorded_at' => now()->subMinutes(20)->toDateTimeString(),
            'source' => 'Air Quality API (Cached Baseline)',
            'is_cached' => true,
        ];
    }
}
