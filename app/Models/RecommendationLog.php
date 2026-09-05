<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'preferred_crowd',
        'preferred_time',
        'transport_mode',
        'recommendations_json',
    ];

    protected $casts = [
        'recommendations_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
