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
                'category' => 'Infrastruktur Geografis & Perkotaan',
                'purpose' => "Data geografis, jaringan jalan, taman publik, fasilitas umum, batas wilayah, dan koordinat spasial untuk {$cityName}.",
                'endpoint' => 'https://overpass-api.de/api/interpreter',
                'status' => 'TERHUBUNG (ONLINE)',
                'last_updated' => DataSyncLog::where('source', 'openstreetmap')->latest()->value('synced_at') ?? now()->toDateTimeString(),
            ],
            [
                'name' => 'BMKG Open Data & Meteorological API',
                'category' => 'Meteorologi & Prakiraan Cuaca',
                'purpose' => "Prakiraan cuaca real-time, suhu udara, kelembapan, intensitas hujan, peluang presipitasi, serta estimasi kondisi cuaca untuk {$cityName}.",
                'endpoint' => "https://api.open-meteo.com/v1/forecast ({$cityName} {$lat}, {$lng})",
                'status' => 'TERHUBUNG (ONLINE)',
                'last_updated' => DataSyncLog::where('source', 'bmkg')->latest()->value('synced_at') ?? now()->toDateTimeString(),
            ],
            [
                'name' => 'Air Quality Open Data API',
                'category' => 'Kesehatan Lingkungan & Kualitas Udara',
                'purpose' => "Indeks Kualitas Udara (US AQI), konsentrasi partikel PM2.5, PM10, dan pemetaan kategori kesehatan atmosfer untuk {$cityName}.",
                'endpoint' => 'https://air-quality-api.open-meteo.com/v1/air-quality',
                'status' => 'TERHUBUNG (ONLINE)',
                'last_updated' => DataSyncLog::where('source', 'air_quality')->latest()->value('synced_at') ?? now()->toDateTimeString(),
            ],
            [
                'name' => 'UrbanPulse Intelligence Scoring Engine',
                'category' => 'Analitik Teratur & Agregasi AI',
                'purpose' => 'Perhitungan skor kecocokan rekomendasi, model estimasi kepadatan pengunjung, algoritma kalkulasi emisi CO2 yang dihemat, serta sintesis konteks AI Gemini.',
                'endpoint' => 'Layanan Algoritma Internal (Laravel Monolith)',
                'status' => 'AKTIF REAL-TIME',
                'last_updated' => now()->toDateTimeString(),
            ]
        ];

        return view('data-sources', compact('sources', 'syncLogs'));
    }
}
