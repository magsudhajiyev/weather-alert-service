<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'type',
        'value',
        'notified_at',
    ];

    protected $casts = [
        'value' => 'float',
        'notified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the weather alert.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the city that the weather alert is for.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope a query to only include precipitation alerts.
     */
    public function scopePrecipitation($query)
    {
        return $query->where('type', 'precipitation');
    }

    /**
     * Scope a query to only include UV alerts.
     */
    public function scopeUv($query)
    {
        return $query->where('type', 'uv');
    }
}