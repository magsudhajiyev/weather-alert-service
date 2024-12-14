<?php

namespace App\Services\Notifications;

use App\Contracts\Services\NotificationServiceInterface;
use App\Models\User;
use App\DTOs\WeatherAlertData;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeatherAlert;

class EmailNotificationService implements NotificationServiceInterface
{
    public function sendWeatherAlert(User $user, WeatherAlertData $alertData): void
    {
        Mail::to($user)->send(new WeatherAlert($alertData));
    }
}
