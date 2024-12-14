<?php

namespace App\Contracts\Services;

use App\DTOs\WeatherData;
use App\Models\City;

interface WeatherServiceInterface
{
    public function getCurrentWeather(City $city): WeatherData;
    public function shouldSendPrecipitationAlert(float $currentValue, float $threshold): bool;
    public function shouldSendUVAlert(float $currentValue, float $threshold): bool;
}