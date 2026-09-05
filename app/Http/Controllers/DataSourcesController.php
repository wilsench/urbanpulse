<?php

namespace App\Http\Controllers;

use App\Models\DataSyncLog;

class DataSourcesController extends Controller
{
    public function index()
    {
        $activeCity = view()->shared('activeCity');
        $cityName = $activeCity ? $activeCity->name : 'Kota Bogor';
        $lat = $activeCity ? $activeCity->latitude : -6.5971;
        $lng = $activeCity ? $activeCity->longitude : 106.7949;

        $syncLogs = DataSyncLog::latest()->take(10)->get();

        $sources = [
            [
                'name' => 'OpenStreetMap (OSM)',
                'category' => 'Geographic & Urban Infrastructure',
                'purpose' => "Geographic data, roads, parks, public facilities, boundaries, and spatial coordinates for {$cityName}.",
                'endpoint' => 'https://overpass-api.de/api/interpreter',
                'status' => 'ONLINE',
                'last_updated' => DataSyncLog::where('source', 'openstreetmap')->latest()->value('synced_at') ?? now()->toDateTimeString(),
            ],
            [
                'name' => 'BMKG Open Data & Meteorological API',
                'category' => 'Meteorology & Forecast',
                'purpose' => "Real-time weather, temperature, humidity, rainfall, rain probability, and short-term forecasts for {$cityName}.",
                'endpoint' => "https://api.open-meteo.com/v1/forecast ({$cityName} {$lat}, {$lng})",
                'status' => 'ONLINE',
                'last_updated' => DataSyncLog::where('source', 'bmkg')->latest()->value('synced_at') ?? now()->toDateTimeString(),
            ],
            [
                'name' => 'Air Quality Open Data API',
                'category' => 'Environmental Health & Air Quality',
                'purpose' => "US AQI, PM2.5 concentration, PM10, and atmospheric health categorization for {$cityName}.",
                'endpoint' => 'https://air-quality-api.open-meteo.com/v1/air-quality',
                'status' => 'ONLINE',
                'last_updated' => DataSyncLog::where('source', 'air_quality')->latest()->value('synced_at') ?? now()->toDateTimeString(),
            ],
            [
                'name' => 'UrbanPulse Intelligence Scoring Engine',
                'category' => 'Derived Analytics & AI Grounding',
                'purpose' => 'Calculated match scores, crowd estimation models, CO2 avoided algorithms, and Gemini AI context synthesis.',
                'endpoint' => 'Internal Algorithmic Service (Laravel Monolith)',
                'status' => 'ACTIVE',
                'last_updated' => now()->toDateTimeString(),
            ]
        ];

        return view('data-sources', compact('sources', 'syncLogs'));
    }
}
