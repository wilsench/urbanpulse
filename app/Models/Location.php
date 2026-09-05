<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'slug',
        'description',
        'category',
        'latitude',
        'longitude',
        'address',
        'green_score',
        'accessibility_score',
        'bike_friendly',
        'walking_score',
        'source',
        'source_id',
        'external_id',
        'website',
        'phone',
        'opening_hours',
        'accessibility_info',
        'rating',
        'is_active',
        'is_verified',
        'last_seen_at',
        'last_synced_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'green_score' => 'integer',
        'accessibility_score' => 'integer',
        'walking_score' => 'integer',
        'bike_friendly' => 'boolean',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'rating' => 'float',
        'last_seen_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
