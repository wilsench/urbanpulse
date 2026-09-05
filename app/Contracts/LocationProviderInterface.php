<?php

namespace App\Contracts;

use App\Models\City;

interface LocationProviderInterface
{
    /**
     * Discover locations for a specified city within a given search radius in meters.
     *
     * @param City $city
     * @param int $radiusMeters
     * @return array Array of normalized location data arrays
     */
    public function discoverLocations(City $city, int $radiusMeters = 10000): array;

    /**
     * Get the provider string identifier (e.g. 'openstreetmap').
     */
    public function getProviderKey(): string;
}
