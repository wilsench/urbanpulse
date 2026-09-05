<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationSyncLog extends Model
{
    use HasFactory;

    protected $table = 'location_sync_logs';

    protected $fillable = [
        'city_id',
        'provider',
        'search_radius',
        'discovered_count',
        'created_count',
        'updated_count',
        'duplicate_count',
        'skipped_count',
        'deactivated_count',
        'status',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'search_radius' => 'integer',
        'discovered_count' => 'integer',
        'created_count' => 'integer',
        'updated_count' => 'integer',
        'duplicate_count' => 'integer',
        'skipped_count' => 'integer',
        'deactivated_count' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
