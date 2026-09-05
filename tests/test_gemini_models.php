<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$key = env('GEMINI_API_KEY');

$models = ['gemini-1.5-flash-latest', 'gemini-1.5-pro', 'gemini-pro', 'gemini-2.0-flash-exp'];

foreach ($models as $m) {
    try {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$m}:generateContent?key={$key}";
        $res = Http::timeout(5)->post($url, [
            'contents' => [['parts' => [['text' => 'Halo']]]]
        ]);
        echo "{$m} STATUS: " . $res->status() . "\n";
    } catch (\Throwable $e) {
        echo "{$m} ERR: " . $e->getMessage() . "\n";
    }
}
