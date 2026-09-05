<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCityController extends Controller
{
    /**
     * Master dataset of Indonesian Regencies & Cities with coordinates.
     */
    protected array $indonesianCitiesMaster = [
        ['name' => 'Kota Bogor', 'province' => 'Jawa Barat', 'latitude' => -6.5971, 'longitude' => 106.7949, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'latitude' => -6.2615, 'longitude' => 106.8106, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Jakarta Pusat', 'province' => 'DKI Jakarta', 'latitude' => -6.1805, 'longitude' => 106.8284, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Jakarta Utara', 'province' => 'DKI Jakarta', 'latitude' => -6.1384, 'longitude' => 106.8642, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Jakarta Barat', 'province' => 'DKI Jakarta', 'latitude' => -6.1683, 'longitude' => 106.7589, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Jakarta Timur', 'province' => 'DKI Jakarta', 'latitude' => -6.2250, 'longitude' => 106.9004, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Depok', 'province' => 'Jawa Barat', 'latitude' => -6.4025, 'longitude' => 106.7942, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Bekasi', 'province' => 'Jawa Barat', 'latitude' => -6.2383, 'longitude' => 106.9756, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Tangerang', 'province' => 'Banten', 'latitude' => -6.1783, 'longitude' => 106.6300, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Tangerang Selatan', 'province' => 'Banten', 'latitude' => -6.2886, 'longitude' => 106.7179, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Bandung', 'province' => 'Jawa Barat', 'latitude' => -6.9175, 'longitude' => 107.6191, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Cimahi', 'province' => 'Jawa Barat', 'latitude' => -6.8722, 'longitude' => 107.5422, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Semarang', 'province' => 'Jawa Tengah', 'latitude' => -6.9667, 'longitude' => 110.4167, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Surakarta (Solo)', 'province' => 'Jawa Tengah', 'latitude' => -7.5755, 'longitude' => 110.8243, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Yogyakarta', 'province' => 'DI Yogyakarta', 'latitude' => -7.7956, 'longitude' => 110.3695, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Surabaya', 'province' => 'Jawa Timur', 'latitude' => -7.2575, 'longitude' => 112.7521, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Malang', 'province' => 'Jawa Timur', 'latitude' => -7.9666, 'longitude' => 112.6326, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Denpasar', 'province' => 'Bali', 'latitude' => -8.6705, 'longitude' => 115.2126, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Medan', 'province' => 'Sumatera Utara', 'latitude' => 3.5952, 'longitude' => 98.6722, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Palembang', 'province' => 'Sumatera Selatan', 'latitude' => -2.9761, 'longitude' => 104.7754, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Pekanbaru', 'province' => 'Riau', 'latitude' => 0.5071, 'longitude' => 101.4478, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Batam', 'province' => 'Kepulauan Riau', 'latitude' => 1.1301, 'longitude' => 104.0529, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Bandar Lampung', 'province' => 'Lampung', 'latitude' => -5.4500, 'longitude' => 105.2667, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Padang', 'province' => 'Sumatera Barat', 'latitude' => -0.9471, 'longitude' => 100.4172, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Banda Aceh', 'province' => 'Aceh', 'latitude' => 5.5483, 'longitude' => 95.3238, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Pontianak', 'province' => 'Kalimantan Barat', 'latitude' => -0.0263, 'longitude' => 109.3425, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kota Banjarmasin', 'province' => 'Kalimantan Selatan', 'latitude' => -3.3194, 'longitude' => 114.5908, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Balikpapan', 'province' => 'Kalimantan Timur', 'latitude' => -1.2379, 'longitude' => 116.8529, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Samarinda', 'province' => 'Kalimantan Timur', 'latitude' => -0.5022, 'longitude' => 117.1536, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Nusantara (IKN)', 'province' => 'Kalimantan Timur', 'latitude' => -0.9700, 'longitude' => 116.7000, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Makassar', 'province' => 'Sulawesi Selatan', 'latitude' => -5.1477, 'longitude' => 119.4327, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Manado', 'province' => 'Sulawesi Utara', 'latitude' => 1.4748, 'longitude' => 124.8428, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Palu', 'province' => 'Sulawesi Tengah', 'latitude' => -0.8917, 'longitude' => 119.8707, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Kendari', 'province' => 'Sulawesi Tenggara', 'latitude' => -3.9985, 'longitude' => 122.5127, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Mataram', 'province' => 'Nusa Tenggara Barat', 'latitude' => -8.5833, 'longitude' => 116.1167, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Kupang', 'province' => 'Nusa Tenggara Timur', 'latitude' => -10.1772, 'longitude' => 123.6070, 'timezone' => 'Asia/Makassar'],
        ['name' => 'Kota Ambon', 'province' => 'Maluku', 'latitude' => -3.6954, 'longitude' => 128.1814, 'timezone' => 'Asia/Jayapura'],
        ['name' => 'Kota Jayapura', 'province' => 'Papua', 'latitude' => -2.5489, 'longitude' => 140.7197, 'timezone' => 'Asia/Jayapura'],
        ['name' => 'Kabupaten Bogor', 'province' => 'Jawa Barat', 'latitude' => -6.5500, 'longitude' => 106.8000, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kabupaten Sleman', 'province' => 'DI Yogyakarta', 'latitude' => -7.7167, 'longitude' => 110.3500, 'timezone' => 'Asia/Jakarta'],
        ['name' => 'Kabupaten Badung', 'province' => 'Bali', 'latitude' => -8.5833, 'longitude' => 115.1833, 'timezone' => 'Asia/Makassar'],
    ];

    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        
        $query = City::withCount('locations');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('province', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $cities = $query->orderBy('is_active', 'desc')->orderBy('name', 'asc')->paginate(12);

        $totalCities = City::count();
        $activeCities = City::where('is_active', true)->count();
        $totalLocations = \App\Models\Location::count();

        return view('admin.cities.index', compact('cities', 'search', 'totalCities', 'activeCities', 'totalLocations'));
    }

    public function create(): View
    {
        return view('admin.cities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'timezone' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Check if slug exists, if so generate unique
        $originalSlug = $validated['slug'];
        $count = 1;
        while (City::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$originalSlug}-{$count}";
            $count++;
        }

        City::create($validated);

        return redirect()->route('admin.cities.index')->with('success', "Kota {$validated['name']} berhasil ditambahkan ke database!");
    }

    public function edit(City $city): View
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'timezone' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        if ($city->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;
            while (City::where('slug', $slug)->where('id', '!=', $city->id)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', "Data kota {$city->name} berhasil diperbarui.");
    }

    public function destroy(City $city): RedirectResponse
    {
        if ($city->locations()->count() > 0) {
            return redirect()->back()->with('error', "Gagal menghapus kota {$city->name} karena masih memiliki lokasi terhubung. Hapus atau pindahkan lokasinya terlebih dahulu.");
        }

        $cityName = $city->name;
        $city->delete();

        return redirect()->route('admin.cities.index')->with('success', "Kota {$cityName} telah dihapus dari database.");
    }

    public function toggleActive(City $city): RedirectResponse
    {
        $city->update([
            'is_active' => !$city->is_active
        ]);

        $status = $city->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Status kota {$city->name} berhasil {$status}.");
    }

    /**
     * API Endpoint to live-search Indonesian Cities dataset.
     */
    public function searchIndonesianCities(Request $request): JsonResponse
    {
        $q = strtolower(trim($request->query('q', '')));

        if (empty($q)) {
            return response()->json(array_slice($this->indonesianCitiesMaster, 0, 15));
        }

        $filtered = array_filter($this->indonesianCitiesMaster, function ($city) use ($q) {
            return str_contains(strtolower($city['name']), $q) || 
                   str_contains(strtolower($city['province']), $q);
        });

        return response()->json(array_values($filtered));
    }

    /**
     * One-click bulk sync/import preset Indonesian cities into the DB.
     */
    public function syncIndonesiaCities(): RedirectResponse
    {
        $count = 0;
        foreach ($this->indonesianCitiesMaster as $c) {
            $slug = Str::slug($c['name']);
            $existing = City::where('slug', $slug)->first();
            if (!$existing) {
                City::create([
                    'name' => $c['name'],
                    'slug' => $slug,
                    'province' => $c['province'],
                    'country' => 'Indonesia',
                    'latitude' => $c['latitude'],
                    'longitude' => $c['longitude'],
                    'timezone' => $c['timezone'],
                    'description' => "Pusat perkotaan di Provinsi {$c['province']} dengan pemantauan lingkungan dan mobilitas ramah lingkungan.",
                    'is_active' => true,
                ]);
                $count++;
            }
        }

        return redirect()->route('admin.cities.index')->with('success', "Berhasil menyinkronkan {$count} kota baru di Indonesia ke database!");
    }
}
