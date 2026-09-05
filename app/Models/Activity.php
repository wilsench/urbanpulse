<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_id',
        'action_type',
        'distance_km',
        'co2_avoided_kg',
        'eco_points_earned',
        'notes',
        'performed_at',
    ];

    protected $casts = [
        'distance_km' => 'float',
        'co2_avoided_kg' => 'float',
        'eco_points_earned' => 'integer',
        'performed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
