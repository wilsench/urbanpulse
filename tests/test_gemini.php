<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$key = env('GEMINI_API_KEY');

// Test 3: gemini-3.6-flash
$url3 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$key}";
$res3 = Http::timeout(10)->post($url3, [
    'contents' => [
        [
            'parts' => [
                ['text' => 'Hai, siapa nama kamu? Jawab dalam 1 kalimat.']
            ]
        ]
    ]
]);

echo "gemini-3.6-flash STATUS: " . $res3->status() . "\n";
echo "gemini-3.6-flash BODY: " . $res3->body() . "\n\n";

// List available models
$listUrl = "https://generativelanguage.googleapis.com/v1beta/models?key={$key}";
$listRes = Http::timeout(10)->get($listUrl);
echo "ListModels STATUS: " . $listRes->status() . "\n";
$models = $listRes->json()['models'] ?? [];
foreach ($models as $m) {
    echo "- " . $m['name'] . "\n";
}
