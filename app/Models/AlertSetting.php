<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'precipitation_threshold',
        'uv_index_threshold',
        'is_active',
    ];

    protected $casts = [
        'precipitation_threshold' => 'float',
        'uv_index_threshold' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the alert setting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the city that the alert setting is for.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}