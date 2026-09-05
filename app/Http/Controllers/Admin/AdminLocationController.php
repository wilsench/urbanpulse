<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncCityLocationsJob;
use App\Models\City;
use App\Models\Location;
use App\Models\LocationSyncLog;
use App\Services\LocationSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminLocationController extends Controller
{
    public function index()
    {
        $activeCity = view()->shared('activeCity');
        $cities = City::where('is_active', true)->get();

        $query = Location::with('city');
        if ($activeCity) {
            $query->where('city_id', $activeCity->id);
        }

        $locations = $query->latest()->paginate(15);
        $syncLogs = LocationSyncLog::with('city')->latest()->take(5)->get();

        return view('admin.locations.index', compact('locations', 'cities', 'activeCity', 'syncLogs'));
    }

    public function syncLocations(Request $request, LocationSyncService $syncService)
    {
        $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
            'radius' => ['required', 'integer', 'between:1000,50000'],
        ]);

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

        // Process directly/dispatch job safely
        if (config('queue.default') === 'sync') {
            $result = $syncService->syncCityLocations($city, $radius, $syncLog->id);
        } else {
            // Dispatch background queue job
            SyncCityLocationsJob::dispatch($city, $radius, $syncLog->id);
            
            // Also execute in background or synchronously for quick response
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

    public function syncStatus()
    {
        $activeCity = view()->shared('activeCity');
        $query = LocationSyncLog::with('city')->latest();
        if ($activeCity) {
            $query->where('city_id', $activeCity->id);
        }
        $latestLog = $query->first();

        return response()->json([
            'latest_log' => $latestLog,
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
