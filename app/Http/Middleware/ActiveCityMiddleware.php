<?php

namespace App\Http\Middleware;

use App\Models\City;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ActiveCityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allCities = City::where('is_active', true)->get();

        if ($allCities->isEmpty()) {
            // Fallback default city if table is empty
            $activeCity = new City([
                'id' => 1,
                'name' => 'Kota Bogor',
                'slug' => 'kota-bogor',
                'province' => 'Jawa Barat',
                'country' => 'Indonesia',
                'latitude' => -6.5971,
                'longitude' => 106.7949,
            ]);
            $allCities = collect([$activeCity]);
        } else {
            $selectedSlug = $request->query('city') ?? session('active_city_slug');
            
            if ($selectedSlug) {
                $activeCity = $allCities->firstWhere('slug', $selectedSlug);
            }

            if (!isset($activeCity) || !$activeCity) {
                $activeCity = $allCities->first();
            }

            session([
                'active_city_id' => $activeCity->id,
                'active_city_slug' => $activeCity->slug,
            ]);
        }

        View::share('activeCity', $activeCity);
        View::share('allCities', $allCities);

        return $next($request);
    }
}
