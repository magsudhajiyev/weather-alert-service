<?php

namespace App\Services\Weather;

use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use App\Contracts\Repositories\WeatherAlertRepositoryInterface;
use App\Contracts\Services\WeatherServiceInterface;
use App\Contracts\Services\NotificationServiceInterface;
use App\DTOs\WeatherAlertData;
use Carbon\Carbon;

class WeatherAlertService
{
    public function __construct(
        private readonly WeatherServiceInterface $weatherService,
        private readonly NotificationServiceInterface $notificationService,
        private readonly AlertSettingsRepositoryInterface $alertSettingsRepository,
        private readonly WeatherAlertRepositoryInterface $weatherAlertRepository
    ) {}

    public function processAlerts(): void
    {
        $activeSettings = $this->alertSettingsRepository->getActiveSettings();

        foreach ($activeSettings as $setting) {
            $weatherData = $this->weatherService->getCurrentWeather($setting->city);

            // Check precipitation
            if ($this->weatherService->shouldSendPrecipitationAlert($weatherData->precipitation, $setting->precipitation_threshold)) {
                $this->handleAlert(
                    $setting->user,
                    $setting->city,
                    'precipitation',
                    $weatherData->precipitation,
                    $setting->precipitation_threshold
                );
            }

            // Check UV index
            if ($this->weatherService->shouldSendUVAlert($weatherData->uvIndex, $setting->uv_index_threshold)) {
                $this->handleAlert(
                    $setting->user,
                    $setting->city,
                    'uv',
                    $weatherData->uvIndex,
                    $setting->uv_index_threshold
                );
            }
        }
    }

    private function handleAlert($user, $city, $type, $value, $threshold): void
    {
        // Check if we've sent an alert recently (within last hour)
        if ($this->weatherAlertRepository->hasRecentAlert($user, $city, $type, Carbon::now()->subHour())) {
            return;
        }

        // Create alert record
        $this->weatherAlertRepository->create([
            'user_id' => $user->id,
            'city_id' => $city->id,
            'type' => $type,
            'value' => $value,
            'notified_at' => Carbon::now(),
        ]);

        // Send notification
        $alertData = new WeatherAlertData(
            type: $type,
            value: $value,
            threshold: $threshold,
            cityName: $city->name,
            countryCode: $city->country_code
        );

        $this->notificationService->sendWeatherAlert($user, $alertData);
    }
}