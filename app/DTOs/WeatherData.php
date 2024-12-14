<?php

namespace App\DTOs;

class WeatherData
{
    public function __construct(
        public readonly float $precipitation,
        public readonly float $uvIndex,
        public readonly string $cityName,
        public readonly string $countryCode
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            precipitation: $data['precipitation'] ?? 0.0,
            uvIndex: $data['uv_index'] ?? 0.0,
            cityName: $data['city_name'],
            countryCode: $data['country_code']
        );
    }

    // Helper method to get data as array if needed
    public function toArray(): array
    {
        return [
            'precipitation' => $this->precipitation,
            'uv_index' => $this->uvIndex,
            'city_name' => $this->cityName,
            'country_code' => $this->countryCode
        ];
    }
}