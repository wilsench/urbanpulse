<?php

namespace App\Services;

class ImpactCalculationService
{
    /**
     * Calculate estimated CO2 avoided (kg) and Eco Points.
     * Documented methodology:
     * - Average passenger vehicle emits ~210g CO2/km (0.210 kg/km).
     * - Walking / Cycling avoids 100% of vehicle emissions (0.210 kg CO2/km).
     * - Public Transport saves ~66% vs solo vehicle driving (0.140 kg CO2/km).
     * - Carpooling saves ~50% per passenger (0.105 kg CO2/km).
     * Labelled as "Estimated CO2 avoided by UrbanPulse".
     */
    public function calculateImpact(string $actionType, float $distanceKm): array
    {
        $actionType = strtolower($actionType);

        $co2PerKm = match ($actionType) {
            'walking' => 0.210,
            'cycling' => 0.210,
            'public_transport' => 0.140,
            'carpooling' => 0.105,
            default => 0.050,
        };

        $pointsPerKm = match ($actionType) {
            'walking' => 5,
            'cycling' => 10,
            'public_transport' => 8,
            'carpooling' => 6,
            default => 3,
        };

        $co2Avoided = round($distanceKm * $co2PerKm, 3);
        $pointsEarned = (int) round($distanceKm * $pointsPerKm);

        return [
            'co2_avoided_kg' => max(0.01, $co2Avoided),
            'eco_points' => max(1, $pointsEarned),
            'methodology' => 'Calculated using IPCC & Transport Emissions Baseline (0.210 kg CO2/km solo car baseline)',
            'disclaimer' => 'Estimated CO2 avoided based on standard urban vehicle emission factors.',
        ];
    }
}
