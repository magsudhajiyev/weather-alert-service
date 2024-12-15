<?php

namespace App\DTOs;

class WeatherData
{
    public function __construct(
        public readonly float $precipitation,
        public readonly float $uvIndex,
        public readonly string $cityName,
        public readonly string $countryCode,
        public readonly string $description
    ) {}

    public function toArray(): array
    {
        return [
            'precipitation' => $this->precipitation,
            'uv_index' => $this->uvIndex,
            'city_name' => $this->cityName,
            'country_code' => $this->countryCode,
            'description' => $this->description
        ];
    }

    public function getPrecipitationText(): string
    {
        return "{$this->precipitation} mm/h";
    }

    public function getUVText(): string
    {
        if ($this->uvIndex >= 8) {
            return "Very High ({$this->uvIndex})";
        } elseif ($this->uvIndex >= 6) {
            return "High ({$this->uvIndex})";
        } elseif ($this->uvIndex >= 3) {
            return "Moderate ({$this->uvIndex})";
        } else {
            return "Low ({$this->uvIndex})";
        }
    }
}