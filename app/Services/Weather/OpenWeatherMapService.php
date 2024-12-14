<?php

namespace App\Services\Weather;

use App\Contracts\Services\WeatherServiceInterface;
use App\DTOs\WeatherData;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OpenWeatherMapService implements WeatherServiceInterface
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.openweathermap.org/data/2.5';
    }

    public function getCurrentWeather(City $city): WeatherData
    {
        $cacheKey = "weather_data_{$city->id}";

        return Cache::remember($cacheKey, 900, function () use ($city) {
            // Temporary dummy data
            return new WeatherData(
                precipitation: 0.0,
                uvIndex: 5.0,
                cityName: $city->name,
                countryCode: $city->country_code
            );
        });
    }

    public function shouldSendPrecipitationAlert(float $currentValue, float $threshold): bool
    {
        return $currentValue >= $threshold;
    }

    public function shouldSendUVAlert(float $currentValue, float $threshold): bool
    {
        return $currentValue >= $threshold;
    }
}