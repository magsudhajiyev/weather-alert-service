<?php

namespace App\Services\Weather;

use App\Contracts\Services\WeatherServiceInterface;
use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use App\Mail\WeatherAlert;
use App\Models\WeatherNotification;
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
            // Create in-app notification
            WeatherNotification::create([
                'user_id' => $user->id,
                'city_id' => $weatherData->cityId,  
                'type' => $alertType,
                'current_value' => $alertType === 'precipitation' ? $weatherData->precipitation : $weatherData->uvIndex,
                'threshold_value' => $threshold,
                'description' => $weatherData->description,
            ]);

            // Send email notification
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
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send weather alert', [
                'user_id' => $user->id,
                'city' => $weatherData->cityName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }    
}