<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Location;
use App\Models\LocationSyncLog;
use App\Services\External\OverpassLocationProvider;
use App\Services\LocationSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationSyncTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_overpass_query_builder_constructs_valid_nwr_queries(): void
    {
        $provider = new OverpassLocationProvider();
        $query = $provider->buildOverpassQuery(-6.5971, 106.7949, 10000);

        $this->assertStringContainsString('[out:json][timeout:20];', $query);
        $this->assertStringContainsString('nwr["leisure"="park"](around:10000,-6.597100,106.794900);', $query);
        $this->assertStringContainsString('out center 150;', $query);
    }

    public function test_overpass_provider_parses_nodes_ways_and_relations_correctly(): void
    {
        $provider = new OverpassLocationProvider();

        $rawElements = [
            [
                'type' => 'node',
                'id' => 101,
                'lat' => -6.5910,
                'lon' => 106.7960,
                'tags' => [
                    'name' => 'Taman Kencana Test',
                    'leisure' => 'park',
                    'addr:street' => 'Jalan Kencana',
                    'wheelchair' => 'yes',
                ],
            ],
            [
                'type' => 'way',
                'id' => 202,
                'center' => [
                    'lat' => -6.5920,
                    'lon' => 106.7970,
                ],
                'tags' => [
                    'name' => 'Stadion Lapangan Sempur',
                    'leisure' => 'stadium',
                ],
            ],
            [
                'type' => 'node',
                'id' => 303,
                'lat' => -6.5930,
                'lon' => 106.7980,
                'tags' => [
                    // Missing name - should be skipped
                    'leisure' => 'park',
                ],
            ],
        ];

        $normalized = $provider->normalizeElements($rawElements);

        $this->assertCount(2, $normalized);
        $this->assertEquals('node/101', $normalized[0]['external_id']);
        $this->assertEquals('Taman Kencana Test', $normalized[0]['name']);
        $this->assertEquals('nature', $normalized[0]['category']);

        $this->assertEquals('way/202', $normalized[1]['external_id']);
        $this->assertEquals('Stadion Lapangan Sempur', $normalized[1]['name']);
        $this->assertEquals('sports', $normalized[1]['category']);
    }

    public function test_location_sync_service_creates_new_locations_and_prevents_duplicates(): void
    {
        $city = City::first();

        // Mock HTTP response for Overpass API
        Http::fake([
            'https://overpass-api.de/api/interpreter' => Http::response([
                'elements' => [
                    [
                        'type' => 'node',
                        'id' => 99901,
                        'lat' => $city->latitude + 0.001,
                        'lon' => $city->longitude + 0.001,
                        'tags' => [
                            'name' => 'Taman Otomatis OSM Baru',
                            'leisure' => 'park',
                        ],
                    ],
                ]
            ], 200)
        ]);

        $syncService = app(LocationSyncService::class);
        $result = $syncService->syncCityLocations($city, 10000);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(1, $result['created_count']);
        $this->assertEquals(1, $result['discovered_count']);

        $this->assertDatabaseHas('locations', [
            'city_id' => $city->id,
            'external_id' => 'node/99901',
            'name' => 'Taman Otomatis OSM Baru',
            'source' => 'openstreetmap',
        ]);

        // Run sync second time with same data to test deduplication / update behavior
        $result2 = $syncService->syncCityLocations($city, 10000);

        $this->assertEquals('success', $result2['status']);
        $this->assertEquals(0, $result2['created_count']);
        $this->assertEquals(1, $result2['updated_count']);
        $this->assertEquals(1, $result2['duplicate_count']);
    }

    public function test_api_failure_does_not_delete_existing_locations(): void
    {
        $city = City::first();
        $initialCount = Location::where('city_id', $city->id)->count();

        // Mock HTTP failure
        Http::fake([
            'https://overpass-api.de/api/interpreter' => Http::response([], 500)
        ]);

        $syncService = app(LocationSyncService::class);
        $result = $syncService->syncCityLocations($city, 10000);

        $this->assertEquals('partial', $result['status']);
        $this->assertEquals($initialCount, Location::where('city_id', $city->id)->count());

        $this->assertDatabaseHas('location_sync_logs', [
            'city_id' => $city->id,
            'status' => 'partial',
        ]);
    }

    public function test_admin_can_trigger_location_sync_via_post_route(): void
    {
        $admin = \App\Models\User::where('role', 'admin')->first();
        $city = City::first();

        Http::fake([
            'https://overpass-api.de/api/interpreter' => Http::response([
                'elements' => [
                    [
                        'type' => 'node',
                        'id' => 88801,
                        'lat' => $city->latitude,
                        'lon' => $city->longitude,
                        'tags' => [
                            'name' => 'Taman Test Admin Sync',
                            'leisure' => 'park',
                        ],
                    ],
                ]
            ], 200)
        ]);

        $response = $this->actingAs($admin)->post('/admin/locations/sync', [
            'city_id' => $city->id,
            'radius' => 10000,
        ]);

        $response->assertRedirect('/admin/locations');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('locations', [
            'city_id' => $city->id,
            'name' => 'Taman Test Admin Sync',
        ]);
    }
}
