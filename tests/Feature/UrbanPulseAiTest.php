<?php

namespace Tests\Feature;

use App\Models\City;
use App\Services\UrbanPulseAiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrbanPulseAiTest extends TestCase
{
    use RefreshDatabase;

    protected UrbanPulseAiService $aiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->aiService = app(UrbanPulseAiService::class);
    }

    public function test_json_configuration_can_be_loaded(): void
    {
        $config = $this->aiService->getConfig();
        $this->assertIsArray($config);
        $this->assertNotEmpty($config);
        $this->assertEquals('UrbanPulse Assistant', $config['agent']['name']);
    }

    public function test_required_configuration_fields_exist(): void
    {
        $config = $this->aiService->getConfig();
        $this->aiService->validateConfig($config);

        $this->assertArrayHasKey('agent', $config);
        $this->assertArrayHasKey('identity', $config);
        $this->assertArrayHasKey('communication', $config);
        $this->assertArrayHasKey('behavior', $config);
        $this->assertArrayHasKey('data_policy', $config);
        $this->assertArrayHasKey('conversation', $config);
        $this->assertArrayHasKey('supported_tasks', $config);
        $this->assertArrayHasKey('limitations', $config);
        $this->assertArrayHasKey('response_format', $config);
    }

    public function test_system_prompt_contains_configured_agent_name_and_rules(): void
    {
        $prompt = $this->aiService->buildSystemPrompt();

        $this->assertStringContainsString('UrbanPulse Assistant', $prompt);
        $this->assertStringContainsString('COMMUNICATION RULES:', $prompt);
        $this->assertStringContainsString('DATA POLICY:', $prompt);
        $this->assertStringContainsString('LIMITATIONS:', $prompt);
        $this->assertStringContainsString('Allowed Emojis:', $prompt);
    }

    public function test_data_context_is_dynamically_constructed(): void
    {
        $city = City::first();
        $context = $this->aiService->buildDataContext($city);

        $this->assertIsArray($context);
        $this->assertEquals($city->name, $context['city']);
        $this->assertArrayHasKey('weather', $context);
        $this->assertArrayHasKey('air_quality', $context);
        $this->assertArrayHasKey('locations', $context);
    }

    public function test_missing_data_is_represented_as_unavailable(): void
    {
        $context = $this->aiService->buildDataContext(null);
        $this->assertNotNull($context);
        $this->assertArrayHasKey('weather', $context);
        $this->assertArrayHasKey('description', $context['weather']);
    }

    public function test_api_failures_and_fallback_are_handled_gracefully(): void
    {
        $city = City::first();
        $result = $this->aiService->ask('Rekomendasikan tempat santai', null, $city);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('response', $result);
        $this->assertArrayHasKey('source', $result);
        $this->assertNotEmpty($result['response']);
    }

    public function test_assistant_endpoint_returns_json_response(): void
    {
        $response = $this->postJson('/assistant/query', [
            'prompt' => 'Tempat jogging terbaik di kota ini',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'prompt',
                'response',
                'source',
                'timestamp',
            ]);
    }
}
