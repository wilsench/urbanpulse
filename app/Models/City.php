<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'province',
        'country',
        'latitude',
        'longitude',
        'timezone',
        'description',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function syncLogs()
    {
        return $this->hasMany(LocationSyncLog::class);
    }

    public function latestSyncLog()
    {
        return $this->hasOne(LocationSyncLog::class)->latestOfMany();
    }
}
