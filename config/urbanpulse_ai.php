<?php

/*
|--------------------------------------------------------------------------
| UrbanPulse AI Configuration Loader
|--------------------------------------------------------------------------
|
| Loads config/urbanpulse_ai.json into Laravel config system so that
| config('urbanpulse_ai') returns the specification array and is fully
| compatible with php artisan config:cache.
|
*/

$jsonPath = config_path('urbanpulse_ai.json');

if (file_exists($jsonPath)) {
    $decoded = json_decode(file_get_contents($jsonPath), true);
    if (is_array($decoded)) {
        return $decoded;
    }
}

return [];
