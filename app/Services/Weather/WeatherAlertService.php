<?php

namespace App\Services\Weather;

use App\Contracts\Services\WeatherServiceInterface;
use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use App\Mail\WeatherAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class WeatherAlertService
{
    public function __construct(
        private readonly WeatherServiceInterface $weatherService,
        private readonly AlertSettingsRepositoryInterface $alertSettingsRepository
    ) {}

    public function processAlerts(): void
    {
        $alertSettings = $this->alertSettingsRepository->getActiveSettings();

        foreach ($alertSettings as $setting) {
            try {
                $weatherData = $this->weatherService->getCurrentWeather($setting->city);

                // Check precipitation alert
                if ($this->weatherService->shouldSendPrecipitationAlert(
                    $weatherData->precipitation,
                    $setting->precipitation_threshold
                )) {
                    $this->sendAlert(
                        $setting->user,
                        $weatherData,
                        'precipitation',
                        $setting->precipitation_threshold
                    );
                }

                // Check UV alert
                if ($this->weatherService->shouldSendUVAlert(
                    $weatherData->uvIndex,
                    $setting->uv_index_threshold
                )) {
                    $this->sendAlert(
                        $setting->user,
                        $weatherData,
                        'uv',
                        $setting->uv_index_threshold
                    );
                }

            } catch (\Exception $e) {
                Log::error('Error processing weather alert', [
                    'user_id' => $setting->user_id,
                    'city_id' => $setting->city_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function sendAlert($user, $weatherData, string $alertType, float $threshold): void
    {
        try {
            Mail::to($user)->send(new WeatherAlert(
                user: $user,
                weatherData: $weatherData,
                alertType: $alertType,
                threshold: $threshold
            ));

            Log::info('Weather alert sent', [
                'user_id' => $user->id,
                'city' => $weatherData->cityName,
                'type' => $alertType,
                'value' => $alertType === 'precipitation' ? $weatherData->precipitation : $weatherData->uvIndex
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send weather alert email', [
                'user_id' => $user->id,
                'city' => $weatherData->cityName,
                'error' => $e->getMessage()
            ]);
        }
    }
}