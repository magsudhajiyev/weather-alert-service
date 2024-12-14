<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 
        'email', 
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the alert settings for the user.
     */
    public function alertSettings()
    {
        return $this->hasMany(AlertSetting::class);
    }

    /**
     * Get the weather alerts for the user.
     */
    public function weatherAlerts()
    {
        return $this->hasMany(WeatherAlert::class);
    }

    /**
     * Get the cities that the user is monitoring.
     */
    public function cities()
    {
        return $this->belongsToMany(City::class, 'alert_settings')
            ->withPivot('precipitation_threshold', 'uv_index_threshold', 'is_active')
            ->withTimestamps();
    }
}