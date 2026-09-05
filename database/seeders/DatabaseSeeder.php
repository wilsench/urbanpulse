<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\City;
use App\Models\EnvironmentalData;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Supported Cities
        $bogor = City::updateOrCreate(
            ['slug' => 'kota-bogor'],
            [
                'name' => 'Kota Bogor',
                'province' => 'Jawa Barat',
                'country' => 'Indonesia',
                'latitude' => -6.5971000,
                'longitude' => 106.7949000,
                'timezone' => 'Asia/Jakarta',
                'description' => 'Kota Hujan dengan kawasan konservasi tumbuhan dan ruang terbuka hijau terlindungi.',
                'is_active' => true,
            ]
        );

        $jakarta = City::updateOrCreate(
            ['slug' => 'jakarta-selatan'],
            [
                'name' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'latitude' => -6.2615000,
                'longitude' => 106.8106000,
                'timezone' => 'Asia/Jakarta',
                'description' => 'Pusat integrasi transportasi publik dan taman-taman ramah pejalan kaki.',
                'is_active' => true,
            ]
        );

        $bandung = City::updateOrCreate(
            ['slug' => 'kota-bandung'],
            [
                'name' => 'Kota Bandung',
                'province' => 'Jawa Barat',
                'country' => 'Indonesia',
                'latitude' => -6.9175000,
                'longitude' => 107.6191000,
                'timezone' => 'Asia/Jakarta',
                'description' => 'Kota Kembang dengan hutan kota sejuk dan koridor sepeda kreatif.',
                'is_active' => true,
            ]
        );

        // 2. Admin & Demo Users
        User::firstOrCreate(
            ['email' => 'admin@urbanpulse.id'],
            [
                'name' => 'UrbanPulse Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'eco_points' => 500,
            ]
        );

        $demoUser = User::firstOrCreate(
            ['email' => 'user@urbanpulse.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'eco_points' => 340,
            ]
        );

        // 3. Verified City Locations
        $locationsByCity = [
            $bogor->id => [
                [
                    'name' => 'Kebun Raya Bogor',
                    'slug' => 'kebun-raya-bogor',
                    'description' => 'Hutan kota dan pusat konservasi tumbuhan seluas 87 hektar di pusat Kota Bogor. Sangat ideal untuk jogging, jalan kaki, dan rekreasi alam.',
                    'category' => 'green_space',
                    'latitude' => -6.5976000,
                    'longitude' => 106.7996000,
                    'address' => 'Jl. Ir. H. Juanda No.13, Paledang, Kec. Bogor Tengah',
                    'green_score' => 96,
                    'accessibility_score' => 90,
                    'bike_friendly' => true,
                    'walking_score' => 94,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-NODE-24810293',
                ],
                [
                    'name' => 'Taman Sempur',
                    'slug' => 'taman-sempur',
                    'description' => 'Taman terbuka publik populer untuk olahraga dan aktivitas fisik dengan jalur lari (running track) dan lapangan outdoor.',
                    'category' => 'park',
                    'latitude' => -6.5888000,
                    'longitude' => 106.7972000,
                    'address' => 'Sempur, Kec. Bogor Tengah',
                    'green_score' => 88,
                    'accessibility_score' => 92,
                    'bike_friendly' => true,
                    'walking_score' => 90,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-NODE-59102341',
                ],
                [
                    'name' => 'Taman Kencana',
                    'slug' => 'taman-kencana',
                    'description' => 'Taman hijau bersejarah kawasan warisan budaya dengan pepohonan rindang, bangku taman, dan kawasan pejalan kaki yang sejuk.',
                    'category' => 'park',
                    'latitude' => -6.5895000,
                    'longitude' => 106.7998000,
                    'address' => 'Babakan, Kec. Bogor Tengah',
                    'green_score' => 86,
                    'accessibility_score' => 88,
                    'bike_friendly' => true,
                    'walking_score' => 87,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-NODE-41092834',
                ],
                [
                    'name' => 'Taman Heulang',
                    'slug' => 'taman-heulang',
                    'description' => 'Taman kota luas di kawasan Tanah Sareal dengan fasilitas gym outdoor, lapangan rumput, dan area ramah sepeda.',
                    'category' => 'park',
                    'latitude' => -6.5742000,
                    'longitude' => 106.7981000,
                    'address' => 'Tanah Sareal, Kec. Tanah Sereal',
                    'green_score' => 89,
                    'accessibility_score' => 85,
                    'bike_friendly' => true,
                    'walking_score' => 88,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-NODE-33291024',
                ],
                [
                    'name' => 'Alun-Alun Kota Bogor',
                    'slug' => 'alun-alun-kota-bogor',
                    'description' => 'Pusat integrasi transportasi publik dan ruang terbuka hijau bersebelahan dengan Stasiun Bogor.',
                    'category' => 'public_area',
                    'latitude' => -6.5959000,
                    'longitude' => 106.7905000,
                    'address' => 'Jl. Kapten Muslihat No.22, Cibogor',
                    'green_score' => 78,
                    'accessibility_score' => 98,
                    'bike_friendly' => true,
                    'walking_score' => 95,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-NODE-99201481',
                ]
            ],
            $jakarta->id => [
                [
                    'name' => 'Taman Ayodya Barito',
                    'slug' => 'taman-ayodya-barito',
                    'description' => 'Taman melingkar di Kebayoran Baru dengan kolam terbuka, gazebo tempat belajar, dan trotoar sejuk.',
                    'category' => 'park',
                    'latitude' => -6.2443000,
                    'longitude' => 106.7987000,
                    'address' => 'Jl. Lamandau III, Kebayoran Baru, Jakarta Selatan',
                    'green_score' => 87,
                    'accessibility_score' => 93,
                    'bike_friendly' => true,
                    'walking_score' => 91,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-JKT-01',
                ],
                [
                    'name' => 'Taman Tabebuya Jagakarsa',
                    'slug' => 'taman-tabebuya-jagakarsa',
                    'description' => 'Taman asri dengan deretan pohon Tabebuya bermekaran, kolam teratai, dan jogging track sejuk.',
                    'category' => 'green_space',
                    'latitude' => -6.3341000,
                    'longitude' => 106.8122000,
                    'address' => 'Jl. Moh. Kahfi 1, Jagakarsa, Jakarta Selatan',
                    'green_score' => 92,
                    'accessibility_score' => 84,
                    'bike_friendly' => true,
                    'walking_score' => 88,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-JKT-02',
                ],
                [
                    'name' => 'Hutan Kota Sangga Buana Lebak Bulus',
                    'slug' => 'hutan-kota-sangga-buana',
                    'description' => 'Kawasan konservasi bantaran kali Pesanggrahan dengan jalur penanaman pohon organik dan edukasi lingkungan.',
                    'category' => 'green_space',
                    'latitude' => -6.3050000,
                    'longitude' => 106.7725000,
                    'address' => 'Lebak Bulus, Cilandak, Jakarta Selatan',
                    'green_score' => 95,
                    'accessibility_score' => 80,
                    'bike_friendly' => true,
                    'walking_score' => 85,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-JKT-03',
                ]
            ],
            $bandung->id => [
                [
                    'name' => 'Hutan Kota Babakan Siliwangi',
                    'slug' => 'hutan-kota-babakan-siliwangi',
                    'description' => 'Hutan kota lindung dengan fasilitas forest walk kanopi kayu sepanjang 2 km yang sangat rindang.',
                    'category' => 'green_space',
                    'latitude' => -6.8872000,
                    'longitude' => 107.6105000,
                    'address' => 'Jl. Tamansari No.90, Coblong, Kota Bandung',
                    'green_score' => 97,
                    'accessibility_score' => 89,
                    'bike_friendly' => true,
                    'walking_score' => 96,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-BDG-01',
                ],
                [
                    'name' => 'Taman Lansia Bandung',
                    'slug' => 'taman-lansia-bandung',
                    'description' => 'Taman sejuk bersebelahan dengan Gedung Sate dengan aliran sungai bersih dan bangku refleksi.',
                    'category' => 'park',
                    'latitude' => -6.9022000,
                    'longitude' => 107.6201000,
                    'address' => 'Jl. Citarum, Bandung Wetan, Kota Bandung',
                    'green_score' => 90,
                    'accessibility_score' => 94,
                    'bike_friendly' => true,
                    'walking_score' => 92,
                    'source' => 'openstreetmap',
                    'source_id' => 'OSM-BDG-02',
                ]
            ]
        ];

        foreach ($locationsByCity as $cityId => $locs) {
            $city = City::find($cityId);
            foreach ($locs as $loc) {
                $loc['city_id'] = $cityId;
                $locationModel = Location::updateOrCreate(['slug' => $loc['slug']], $loc);

                // Initial environmental data for each location
                EnvironmentalData::create([
                    'city_id' => $cityId,
                    'location_id' => $locationModel->id,
                    'city' => $city->name,
                    'latitude' => $loc['latitude'],
                    'longitude' => $loc['longitude'],
                    'temperature' => 26.5,
                    'humidity' => 75,
                    'weather_code' => 2,
                    'weather_description' => 'Cerah Berawan (Partly Cloudy)',
                    'rainfall' => 0.0,
                    'rain_probability' => 15,
                    'air_quality_index' => rand(30, 45),
                    'pm25' => rand(8, 12) + 0.5,
                    'air_quality_status' => 'BAIK (GOOD)',
                    'recorded_at' => now(),
                    'source' => 'BMKG Open Data & Open-Meteo Air Quality',
                    'is_cached' => false,
                ]);
            }
        }

        // 4. Demo User Activities
        $bogorLocation = Location::where('slug', 'kebun-raya-bogor')->first();
        $activities = [
            [
                'action_type' => 'cycling',
                'distance_km' => 12.5,
                'co2_avoided_kg' => 2.625,
                'eco_points_earned' => 125,
                'notes' => 'Bersepeda di jalur lingkar hijau',
                'performed_at' => now()->subDays(1),
            ],
            [
                'action_type' => 'walking',
                'distance_km' => 4.2,
                'co2_avoided_kg' => 0.882,
                'eco_points_earned' => 21,
                'notes' => 'Jalan sore di taman terbuka',
                'performed_at' => now()->subDays(2),
            ],
        ];

        foreach ($activities as $act) {
            $act['user_id'] = $demoUser->id;
            $act['location_id'] = $bogorLocation ? $bogorLocation->id : null;
            Activity::create($act);
        }
    }
}
