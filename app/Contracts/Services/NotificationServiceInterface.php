<?php

namespace App\Contracts\Services;

use App\Models\User;
use App\DTOs\WeatherAlertData;

interface NotificationServiceInterface
{
    public function sendWeatherAlert(User $user, WeatherAlertData $alertData): void;
}