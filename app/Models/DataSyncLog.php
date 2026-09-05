<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'status',
        'message',
        'records_synced',
        'synced_at',
    ];

    protected $casts = [
        'records_synced' => 'integer',
        'synced_at' => 'datetime',
    ];
}
