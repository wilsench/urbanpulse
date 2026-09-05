<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class PersonalImpactController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activities = Activity::where('user_id', $user->id)->latest()->get();

        $totalCo2 = $activities->sum('co2_avoided_kg');
        $totalActions = $activities->count();
        $totalEcoPoints = $user->eco_points;

        // Weekly breakdown (last 7 days)
        $weeklyData = Activity::select(
            DB::raw('DATE(performed_at) as date'),
            DB::raw('SUM(co2_avoided_kg) as co2'),
            DB::raw('SUM(distance_km) as distance')
        )
        ->where('user_id', $user->id)
        ->where('performed_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $weeklyLabels = [];
        $weeklyCo2Values = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $dayLabel = now()->subDays($i)->format('D, d M');
            $weeklyLabels[] = $dayLabel;
            $found = $weeklyData->firstWhere('date', $dateStr);
            $weeklyCo2Values[] = $found ? (float) $found->co2 : 0.0;
        }

        // Action Type Breakdown for Chart
        $categoryBreakdown = Activity::select('action_type', DB::raw('SUM(co2_avoided_kg) as total_co2'), DB::raw('COUNT(*) as count'))
            ->where('user_id', $user->id)
            ->groupBy('action_type')
            ->get();

        return view('user.impact', compact(
            'user',
            'totalCo2',
            'totalActions',
            'totalEcoPoints',
            'weeklyLabels',
            'weeklyCo2Values',
            'categoryBreakdown',
            'activities'
        ));
    }
}
