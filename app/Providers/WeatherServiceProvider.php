<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\WeatherServiceInterface;
use App\Contracts\Services\NotificationServiceInterface;
use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use App\Contracts\Repositories\WeatherAlertRepositoryInterface;
use App\Services\Weather\OpenWeatherMapService;
use App\Services\Notifications\EmailNotificationService;
use App\Repositories\AlertSettingsRepository;
use App\Repositories\WeatherAlertRepository;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Weather Service binding
        $this->app->bind(WeatherServiceInterface::class, function ($app) {
            return new OpenWeatherMapService(
                config('services.openweathermap.key', '')
            );
        });

        // Notification Service binding
        $this->app->bind(NotificationServiceInterface::class, EmailNotificationService::class);

        // Repository bindings
        $this->app->bind(AlertSettingsRepositoryInterface::class, function ($app) {
            return new AlertSettingsRepository();
        });

        $this->app->bind(WeatherAlertRepositoryInterface::class, function ($app) {
            return new WeatherAlertRepository();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}