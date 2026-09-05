<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DataSyncLog;
use App\Models\EnvironmentalData;
use App\Services\External\AirQualityService;
use App\Services\External\BmkgService;
use Illuminate\Http\Request;

class AdminEnvironmentalController extends Controller
{
    public function index()
    {
        $environmentalData = EnvironmentalData::with(['city', 'location'])->latest()->paginate(15);
        $syncLogs = DataSyncLog::latest()->paginate(10);

        return view('admin.environment.index', compact('environmentalData', 'syncLogs'));
    }

    public function syncNow(
        Request $request,
        BmkgService $bmkgService,
        AirQualityService $airQualityService
    ) {
        $activeCities = City::where('is_active', true)->orderBy('name', 'asc')->get();

        if ($activeCities->isEmpty()) {
            $activeCity = view()->shared('activeCity');
            $lat = $activeCity ? $activeCity->latitude : -6.5971;
            $lng = $activeCity ? $activeCity->longitude : 106.7949;
            $name = $activeCity ? $activeCity->name : 'Kota Bogor';
            $activeCities = collect([(object)[
                'id' => $activeCity ? $activeCity->id : null,
                'name' => $name,
                'latitude' => $lat,
                'longitude' => $lng,
            ]]);
        }

        $syncedCount = 0;
        foreach ($activeCities as $city) {
            $lat = (float) $city->latitude;
            $lng = (float) $city->longitude;

            // Clear caches and re-fetch fresh data per city
            cache()->forget("bmkg_weather_{$lat}_{$lng}");
            cache()->forget("air_quality_{$lat}_{$lng}");

            try {
                $weather = $bmkgService->getWeather($lat, $lng);
                $airQuality = $airQualityService->getCurrentAirQuality($lat, $lng);

                EnvironmentalData::create([
                    'city_id' => $city->id ?? null,
                    'city' => $city->name,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'temperature' => $weather['temperature'],
                    'humidity' => $weather['humidity'],
                    'weather_code' => $weather['weather_code'],
                    'weather_description' => $weather['weather_description'],
                    'rainfall' => $weather['rainfall'],
                    'rain_probability' => $weather['rain_probability'],
                    'air_quality_index' => $airQuality['air_quality_index'],
                    'pm25' => $airQuality['pm25'],
                    'air_quality_status' => $airQuality['air_quality_status'],
                    'recorded_at' => now(),
                    'source' => $weather['source'] . ' & ' . $airQuality['source'],
                    'is_cached' => false,
                ]);

                $syncedCount++;
            } catch (\Throwable $e) {
                // Log and continue to next city if individual fetch fails
                logger()->error("Environmental sync error for city {$city->name}: " . $e->getMessage());
            }
        }

        $msg = "Sinkronisasi data cuaca & kualitas udara (BMKG / API) untuk {$syncedCount} kota aktif berhasil diselesaikan!";

        if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'synced_count' => $syncedCount,
                'message' => $msg,
            ]);
        }

        return redirect()->route('admin.environment.index')->with('success', $msg);
    }
}
