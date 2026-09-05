<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\User;
use App\Services\CrowdEstimationService;
use App\Services\ImpactCalculationService;
use App\Services\RecommendationScoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrbanPulseTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('URBANPULSE');
    }

    public function test_city_dashboard_renders_bogor_metrics(): void
    {
        $response = $this->get('/city');
        $response->assertStatus(200);
        $response->assertSee('Kota Bogor');
    }

    public function test_interactive_map_renders(): void
    {
        $response = $this->get('/map');
        $response->assertStatus(200);
    }

    public function test_impact_calculation_service(): void
    {
        $service = new ImpactCalculationService();
        $impact = $service->calculateImpact('cycling', 10.0);

        $this->assertEquals(2.1, $impact['co2_avoided_kg']);
        $this->assertEquals(100, $impact['eco_points']);
    }

    public function test_smart_recommendation_process(): void
    {
        Location::create([
            'name' => 'Taman Sempur Test',
            'slug' => 'taman-sempur-test',
            'description' => 'Test park',
            'category' => 'park',
            'latitude' => -6.5888,
            'longitude' => 106.7972,
            'green_score' => 90,
            'accessibility_score' => 90,
            'bike_friendly' => true,
            'walking_score' => 90,
            'source' => 'openstreetmap',
            'is_active' => true,
        ]);

        $response = $this->post('/recommend', [
            'activity_type' => 'exercise',
            'preferred_crowd' => 'low',
            'preferred_time' => 'afternoon',
            'transport_mode' => 'bicycle',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Taman Sempur Test');
    }
}
