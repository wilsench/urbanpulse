<?php

namespace App\Services;

use App\Models\Location;
use Carbon\Carbon;

class CrowdEstimationService
{
    /**
     * Estimate crowd level (LOW, MEDIUM, HIGH) based on UrbanPulse time & location category model.
     * Clearly identified as "Estimated by UrbanPulse".
     */
    public function estimateCrowd(Location $location, ?Carbon $dateTime = null): array
    {
        $dateTime = $dateTime ?? now();
        $hour = $dateTime->hour;
        $isWeekend = $dateTime->isWeekend();

        $score = 50; // base score

        // Category multipliers
        if ($location->category === 'park' || $location->category === 'green_space') {
            if ($isWeekend) {
                if (($hour >= 6 && $hour <= 9) || ($hour >= 15 && $hour <= 18)) {
                    $score += 35; // Morning & afternoon weekend peak
                } else {
                    $score += 15;
                }
            } else {
                if ($hour >= 16 && $hour <= 18) {
                    $score += 20; // Weekday afternoon peak
                } else {
                    $score -= 15;
                }
            }
        } elseif ($location->category === 'transport') {
            if (!$isWeekend && (($hour >= 6 && $hour <= 9) || ($hour >= 17 && $hour <= 19))) {
                $score += 40; // Rush hour
            }
        }

        // Night time decrease
        if ($hour >= 20 || $hour <= 5) {
            $score -= 30;
        }

        $score = max(10, min(95, $score));

        $level = match (true) {
            $score < 40 => 'LOW',
            $score < 70 => 'MEDIUM',
            default => 'HIGH',
        };

        return [
            'level' => $level,
            'numeric_score' => $score,
            'label' => match ($level) {
                'LOW' => 'Rendah (Quiet)',
                'MEDIUM' => 'Sedang (Moderate)',
                'HIGH' => 'Tinggi (Busy)',
            },
            'source' => 'UrbanPulse Crowd Pattern Estimation Model',
        ];
    }
}
