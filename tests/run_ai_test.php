<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Services\UrbanPulseAiService;

echo "=== STARTING URBANPULSE AI SPEC & INTEGRATION VERIFICATION ===\n\n";

$service = app(UrbanPulseAiService::class);

// 1. Config Loading
$config = $service->getConfig();
if (!is_array($config) || empty($config) || $config['agent']['name'] !== 'UrbanPulse Assistant') {
    throw new Exception("FAIL: Config loading failed");
}
echo "[PASS] 1. Config loaded successfully from config('urbanpulse_ai').\n";

// 2. Spec Validation
$service->validateConfig($config);
echo "[PASS] 2. Config validation passed for all 9 required sections.\n";

// 3. System Prompt Generation
$prompt = $service->buildSystemPrompt();
if (!str_contains($prompt, 'UrbanPulse Assistant') || !str_contains($prompt, 'COMMUNICATION RULES:') || !str_contains($prompt, 'DATA POLICY:')) {
    throw new Exception("FAIL: System prompt construction invalid");
}
echo "[PASS] 3. System prompt dynamically built with Agent persona & rules.\n";

// 4. Data Context Construction
$city = City::first();
$context = $service->buildDataContext($city);
if (!isset($context['city']) || $context['city'] !== $city->name) {
    throw new Exception("FAIL: Data context invalid");
}
echo "[PASS] 4. Dynamic UrbanPulse data context successfully built for " . $context['city'] . ".\n";

// 5. Ask / Response Generation
$response = $service->ask('Tempat jogging terbaik di kota ini', null, $city);
if (empty($response['response']) || empty($response['source'])) {
    throw new Exception("FAIL: AI Response empty");
}
echo "[PASS] 5. AI ask() response generated successfully.\n";
echo "AI Response Preview:\n" . substr($response['response'], 0, 150) . "...\n\n";

echo "=== ALL URBANPULSE AI SPECIFICATIONS & TESTS PASSED PERFECTLY ===\n";
