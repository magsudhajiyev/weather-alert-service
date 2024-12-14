<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_code',
        'latitude',
        'longitude',
    ];

    /**
     * Get the users that are monitoring this city.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'alert_settings')
            ->withPivot('precipitation_threshold', 'uv_index_threshold', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get the alert settings for this city.
     */
    public function alertSettings()
    {
        return $this->hasMany(AlertSetting::class);
    }

    /**
     * Get the weather alerts for this city.
     */
    public function weatherAlerts()
    {
        return $this->hasMany(WeatherAlert::class);
    }
}
