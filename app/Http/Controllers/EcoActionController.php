<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Location;
use App\Services\ImpactCalculationService;
use Illuminate\Http\Request;

class EcoActionController extends Controller
{
    protected ImpactCalculationService $impactService;

    public function __construct(ImpactCalculationService $impactService)
    {
        $this->impactService = $impactService;
    }

    public function index()
    {
        $user = auth()->user();
        $activities = Activity::where('user_id', $user->id)->with('location')->latest()->paginate(10);
        $locations = Location::where('is_active', true)->get();

        return view('user.actions', compact('activities', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'action_type' => ['required', 'string', 'in:walking,cycling,public_transport,carpooling,other'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'distance_km' => ['required', 'numeric', 'min:0.1', 'max:500'],
            'performed_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $impact = $this->impactService->calculateImpact($validated['action_type'], (float) $validated['distance_km']);

        $activity = Activity::create([
            'user_id' => auth()->id(),
            'location_id' => $validated['location_id'] ?? null,
            'action_type' => $validated['action_type'],
            'distance_km' => $validated['distance_km'],
            'co2_avoided_kg' => $impact['co2_avoided_kg'],
            'eco_points_earned' => $impact['eco_points'],
            'notes' => $validated['notes'] ?? null,
            'performed_at' => $validated['performed_at'],
        ]);

        // Award Eco Points to user
        $user = auth()->user();
        $user->increment('eco_points', $impact['eco_points']);

        return redirect()->route('actions.index')->with('success', "Aksi hijau berhasil dicatat! Anda menghemat ~{$impact['co2_avoided_kg']} kg CO2 dan mendapatkan +{$impact['eco_points']} Eco Points.");
    }
}
