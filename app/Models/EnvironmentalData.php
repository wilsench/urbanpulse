<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvironmentalData extends Model
{
    use HasFactory;

    protected $table = 'environmental_data';

    protected $fillable = [
        'city_id',
        'location_id',
        'city',
        'latitude',
        'longitude',
        'temperature',
        'humidity',
        'weather_code',
        'weather_description',
        'rainfall',
        'rain_probability',
        'air_quality_index',
        'pm25',
        'air_quality_status',
        'recorded_at',
        'source',
        'is_cached',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'temperature' => 'float',
        'humidity' => 'integer',
        'rainfall' => 'float',
        'rain_probability' => 'integer',
        'air_quality_index' => 'integer',
        'pm25' => 'float',
        'recorded_at' => 'datetime',
        'is_cached' => 'boolean',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
