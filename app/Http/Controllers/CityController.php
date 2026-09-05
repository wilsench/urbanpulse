<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function select(Request $request): RedirectResponse
    {
        $request->validate([
            'city_slug' => 'required|string|exists:cities,slug',
        ]);

        $city = City::where('slug', $request->input('city_slug'))->firstOrFail();

        session([
            'active_city_id' => $city->id,
            'active_city_slug' => $city->slug,
        ]);

        return redirect()->back()->with('success', "Kota aktif berhasil diubah ke {$city->name}.");
    }
}
