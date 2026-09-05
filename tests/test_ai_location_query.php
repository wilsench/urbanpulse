<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Services\UrbanPulseAiService;

$aiService = app(UrbanPulseAiService::class);
$city = City::first();

$prompt = "Mengapa tempat KP. Lebak LPTI Ciparigi direkomendasikan?";
echo "PROMPT: {$prompt}\n\n";

$res = $aiService->ask($prompt, null, $city);

echo "SOURCE: " . $res['source'] . "\n";
echo "RESPONSE:\n" . $res['response'] . "\n";
