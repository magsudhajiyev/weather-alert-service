<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherNotification extends Model
{
    protected $fillable = [
        'user_id',
        'city_id',
        'type',
        'current_value',
        'threshold_value',
        'description',
        'is_read',
    ];

    protected $casts = [
        'current_value' => 'float',
        'threshold_value' => 'float',
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getFormattedValue(): string
    {
        return match($this->type) {
            'precipitation' => number_format($this->current_value, 1) . ' mm/h',
            'uv' => number_format($this->current_value, 1),
            default => number_format($this->current_value, 1)
        };
    }
}