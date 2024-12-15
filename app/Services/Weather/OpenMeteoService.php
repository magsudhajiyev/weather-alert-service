<?php

namespace App\Services\Weather;

use App\Contracts\Services\WeatherServiceInterface;
use App\DTOs\WeatherData;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class OpenMeteoService implements WeatherServiceInterface
{
    public function __construct(
        private readonly string $baseUrl = 'https://api.open-meteo.com/v1'
    ) {}

    public function getCurrentWeather(City $city): WeatherData
    {
        try {
            $weatherData = $this->fetchWeatherData($city);
            Log::info('API Response:', $weatherData);

            // Get the first values from the hourly arrays
            $precipitation = $weatherData['hourly']['precipitation'][0] ?? 0.0;
            $uvIndex = $weatherData['hourly']['uv_index'][0] ?? 0.0;

            return new WeatherData(
                precipitation: (float) $precipitation,
                uvIndex: (float) $uvIndex,
                cityName: $city->name,
                countryCode: $city->country_code,
                description: $this->getWeatherDescription($precipitation, $uvIndex)
            );
        } catch (RequestException $e) {
            Log::error('OpenMeteo API error: ' . $e->getMessage(), [
                'city' => $city->name,
                'error' => $e->response?->json()
            ]);
            throw $e;
        }
    }

    private function fetchWeatherData(City $city): array
    {
        $queryParams = http_build_query([
            'latitude' => $city->latitude,
            'longitude' => $city->longitude,
            'hourly' => 'precipitation,uv_index',
            'forecast_days' => 1,
            'forecast_hours' => 1
        ]);

        $url = "{$this->baseUrl}/forecast?{$queryParams}";
        
        Log::info('Fetching weather data', [
            'city' => $city->name,
            'url' => $url
        ]);

        $response = Http::get($url);
        
        if (!$response->successful()) {
            Log::error('Weather API error', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            throw new RequestException($response);
        }

        return $response->json();
    }

    private function getWeatherDescription(float $precipitation, float $uvIndex): string
    {
        if ($precipitation > 0) {
            if ($precipitation < 2.5) {
                return 'Light rain';
            } elseif ($precipitation < 7.6) {
                return 'Moderate rain';
            } else {
                return 'Heavy rain';
            }
        }

        if ($uvIndex >= 8) {
            return 'Very high UV';
        } elseif ($uvIndex >= 6) {
            return 'High UV';
        } elseif ($uvIndex >= 3) {
            return 'Moderate UV';
        }

        return 'Clear conditions';
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