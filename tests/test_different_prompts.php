<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\City;
use App\Services\UrbanPulseAiService;

$service = app(UrbanPulseAiService::class);
$city = City::where('slug', 'jakarta-selatan')->first() ?: City::first();

$prompts = [
    "Halo, selamat siang!",
    "Bagaimana cuaca di kota ini?",
    "Berapa kualitas udara saat ini?",
    "Berapa CO2 yang saya hemat jika naik sepeda 10 km?",
    "Rekomendasi tempat jogging di Jakarta Selatan"
];

foreach ($prompts as $p) {
    echo "========================================\n";
    echo "USER PROMPT: " . $p . "\n";
    $res = $service->ask($p, null, $city);
    echo "BOT RESPONSE:\n" . $res['response'] . "\n\n";
}
