<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncCityLocationsJob;
use App\Models\City;
use App\Models\Location;
use App\Models\LocationSyncLog;
use App\Services\LocationSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminLocationController extends Controller
{
    public function index(Request $request)
    {
        $selectedCityId = $request->query('city_id');
        $search = $request->query('search', '');

        $activeCity = view()->shared('activeCity');
        $cities = City::where('is_active', true)->orderBy('name', 'asc')->get();

        $query = Location::with('city');

        if ($selectedCityId === 'all') {
            // Show all cities
        } elseif (!empty($selectedCityId)) {
            $query->where('city_id', $selectedCityId);
        } elseif ($activeCity && !$request->has('city_id')) {
            $query->where('city_id', $activeCity->id);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        $locations = $query->latest()->paginate(15);
        $syncLogs = LocationSyncLog::with('city')->latest()->take(5)->get();

        return view('admin.locations.index', compact('locations', 'cities', 'activeCity', 'syncLogs', 'selectedCityId', 'search'));
    }

    public function syncLocations(Request $request, LocationSyncService $syncService)
    {
        $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
            'radius' => ['required', 'integer', 'between:1000,50000'],
        ]);

        $bulkState = Cache::get('urbanpulse_bulk_sync_lock');
        if ($bulkState && !empty($bulkState['is_running'])) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Sinkronisasi massal seluruh kota sedang berjalan di latar belakang. Mohon tunggu hingga selesai.'
                ], 429);
            }
            return redirect()->route('admin.locations.index')->with('error', 'Sinkronisasi massal seluruh kota sedang berjalan di latar belakang.');
        }

        $city = City::findOrFail($request->input('city_id'));
        $radius = (int) $request->input('radius', 10000);

        // Create log record
        $syncLog = LocationSyncLog::create([
            'city_id' => $city->id,
            'provider' => 'openstreetmap',
            'search_radius' => $radius,
            'status' => 'running',
            'started_at' => now(),
        ]);

        if (config('queue.default') === 'sync') {
            $result = $syncService->syncCityLocations($city, $radius, $syncLog->id);
        } else {
            SyncCityLocationsJob::dispatch($city, $radius, $syncLog->id);
            $syncService->syncCityLocations($city, $radius, $syncLog->id);
        }

        $syncLog->refresh();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $syncLog->status,
                'log' => $syncLog,
                'message' => $syncLog->error_message ?? "Proses sinkronisasi lokasi untuk {$city->name} selesai!",
            ]);
        }

        if ($syncLog->status === 'success') {
            $msg = "Sinkronisasi lokasi {$city->name} selesai! Ditemukan: {$syncLog->discovered_count}, Baru: +{$syncLog->created_count}, Diperbarui: {$syncLog->updated_count}.";
            return redirect()->route('admin.locations.index')->with('success', $msg);
        } elseif ($syncLog->status === 'partial') {
            return redirect()->route('admin.locations.index')->with('status', $syncLog->error_message ?? 'Sinkronisasi selesai sebagian.');
        } else {
            return redirect()->route('admin.locations.index')->with('error', 'Sinkronisasi tidak dapat dilakukan saat ini. Data lokasi lama tetap tersedia.');
        }
    }

    public function syncAllCities(Request $request, LocationSyncService $syncService)
    {
        $radius = (int) $request->input('radius', 10000);
        $activeCities = City::where('is_active', true)->get();

        $totalDiscovered = 0;
        $totalCreated = 0;
        $totalUpdated = 0;
        $syncedCities = 0;

        foreach ($activeCities as $city) {
            $syncLog = LocationSyncLog::create([
                'city_id' => $city->id,
                'provider' => 'openstreetmap',
                'search_radius' => $radius,
                'status' => 'running',
                'started_at' => now(),
            ]);

            $syncService->syncCityLocations($city, $radius, $syncLog->id);
            $syncLog->refresh();

            if ($syncLog->status === 'success') {
                $totalDiscovered += $syncLog->discovered_count;
                $totalCreated += $syncLog->created_count;
                $totalUpdated += $syncLog->updated_count;
                $syncedCities++;
            }
        }

        $msg = "Sinkronisasi massal seluruh kota selesai ({$syncedCities} kota aktif)! Total Ditemukan: {$totalDiscovered}, Baru: +{$totalCreated}, Diperbarui: {$totalUpdated}.";
        return redirect()->route('admin.locations.index')->with('success', $msg);
    }

    public function syncAllStep(Request $request, LocationSyncService $syncService)
    {
        $stepIndex = (int) $request->input('step_index', 0);
        $radius = (int) $request->input('radius', 10000);
        
        $activeCities = City::where('is_active', true)->orderBy('name', 'asc')->get();
        $totalCities = count($activeCities);

        $bulkState = Cache::get('urbanpulse_bulk_sync_lock', [
            'batch_id' => 'bulk_' . time(),
            'is_running' => true,
            'current_index' => 0,
            'total_cities' => $totalCities,
            'radius' => $radius,
            'started_at' => now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
            'current_city_name' => '',
            'synced_cities_count' => 0,
            'totals' => ['discovered' => 0, 'created' => 0, 'updated' => 0],
            'logs' => []
        ]);

        if ($stepIndex >= $totalCities || $totalCities === 0) {
            $bulkState['is_running'] = false;
            $bulkState['completed'] = true;
            $bulkState['updated_at'] = now()->toIso8601String();
            Cache::put('urbanpulse_bulk_sync_lock', $bulkState, 3600);

            return response()->json([
                'completed' => true,
                'progress_percent' => 100,
                'synced_cities_count' => $bulkState['synced_cities_count'],
                'totals' => $bulkState['totals'],
                'message' => "Sinkronisasi massal seluruh kota selesai! Total {$bulkState['synced_cities_count']} kota dipindai.",
                'bulk_state' => $bulkState
            ]);
        }

        $targetCity = $activeCities[$stepIndex];
        $bulkState['current_city_name'] = $targetCity->name;
        $bulkState['current_index'] = $stepIndex;
        $bulkState['is_running'] = true;

        $syncLog = LocationSyncLog::create([
            'city_id' => $targetCity->id,
            'provider' => 'openstreetmap',
            'search_radius' => $radius,
            'status' => 'running',
            'started_at' => now(),
        ]);

        $syncService->syncCityLocations($targetCity, $radius, $syncLog->id);
        $syncLog->refresh();

        $logEntry = [
            'step' => $stepIndex + 1,
            'city_id' => $targetCity->id,
            'city_name' => $targetCity->name,
            'province' => $targetCity->province,
            'status' => $syncLog->status,
            'discovered' => $syncLog->discovered_count ?? 0,
            'created' => $syncLog->created_count ?? 0,
            'updated' => $syncLog->updated_count ?? 0,
            'error_message' => $syncLog->error_message,
            'time' => now()->format('H:i:s'),
        ];

        $bulkState['logs'][] = $logEntry;
        if ($syncLog->status === 'success') {
            $bulkState['synced_cities_count']++;
            $bulkState['totals']['discovered'] += ($syncLog->discovered_count ?? 0);
            $bulkState['totals']['created'] += ($syncLog->created_count ?? 0);
            $bulkState['totals']['updated'] += ($syncLog->updated_count ?? 0);
        }

        $nextIndex = $stepIndex + 1;
        $bulkState['current_index'] = $nextIndex;
        $bulkState['updated_at'] = now()->toIso8601String();

        if ($nextIndex >= $totalCities) {
            $bulkState['is_running'] = false;
            $bulkState['completed'] = true;
        }

        Cache::put('urbanpulse_bulk_sync_lock', $bulkState, 3600);

        $progressPercent = round(($nextIndex / $totalCities) * 100);

        return response()->json([
            'completed' => $nextIndex >= $totalCities,
            'step_index' => $nextIndex,
            'total_cities' => $totalCities,
            'progress_percent' => $progressPercent,
            'current_city_name' => $targetCity->name,
            'next_city_name' => $nextIndex < $totalCities ? $activeCities[$nextIndex]->name : null,
            'last_log' => $logEntry,
            'totals' => $bulkState['totals'],
            'bulk_state' => $bulkState,
            'message' => "Selesai mensinkronkan {$targetCity->name} ({$nextIndex}/{$totalCities})"
        ]);
    }

    public function cancelBulkSync()
    {
        Cache::forget('urbanpulse_bulk_sync_lock');
        return response()->json([
            'success' => true,
            'message' => 'Status sinkronisasi massal telah di-reset.'
        ]);
    }

    public function clearSyncLogs(Request $request)
    {
        LocationSyncLog::query()->delete();
        Cache::forget('urbanpulse_bulk_sync_lock');

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Seluruh log dan status sinkronisasi berhasil dibersihkan.'
            ]);
        }

        return redirect()->route('admin.locations.index')->with('success', 'Seluruh log dan status sinkronisasi berhasil dibersihkan.');
    }

    public function syncStatus()
    {
        $activeCity = view()->shared('activeCity');
        $query = LocationSyncLog::with('city')->latest();
        if ($activeCity) {
            $query->where('city_id', $activeCity->id);
        }
        $latestLog = $query->first();

        $bulkState = Cache::get('urbanpulse_bulk_sync_lock');

        return response()->json([
            'latest_log' => $latestLog,
            'bulk_sync' => $bulkState,
            'server_time' => now()->toIso8601String(),
        ]);
    }

    public function create()
    {
        $cities = City::where('is_active', true)->get();
        return view('admin.locations.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string'],
            'green_score' => ['required', 'integer', 'between:0,100'],
            'accessibility_score' => ['required', 'integer', 'between:0,100'],
            'walking_score' => ['required', 'integer', 'between:0,100'],
            'bike_friendly' => ['nullable', 'boolean'],
            'source' => ['required', 'string'],
            'source_id' => ['nullable', 'string'],
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['bike_friendly'] = $request->has('bike_friendly');
        $validated['is_active'] = true;
        $validated['is_verified'] = true;

        Location::create($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    public function edit(Location $location)
    {
        $cities = City::where('is_active', true)->get();
        return view('admin.locations.edit', compact('location', 'cities'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string'],
            'green_score' => ['required', 'integer', 'between:0,100'],
            'accessibility_score' => ['required', 'integer', 'between:0,100'],
            'walking_score' => ['required', 'integer', 'between:0,100'],
            'bike_friendly' => ['nullable', 'boolean'],
            'source' => ['required', 'string'],
            'source_id' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
        ]);

        $validated['bike_friendly'] = $request->has('bike_friendly');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_verified'] = $request->has('is_verified');

        $location->update($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
