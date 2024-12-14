<?php

namespace App\DTOs;

class WeatherAlertData
{
    public function __construct(
        public readonly string $type,
        public readonly float $value,
        public readonly float $threshold,
        public readonly string $cityName,
        public readonly string $countryCode
    ) {}

    public function getMessageSubject(): string
    {
        $alertType = ucfirst($this->type);
        return "{$alertType} Alert for {$this->cityName}, {$this->countryCode}";
    }

    public function getMessageContent(): string
    {
        $unit = $this->type === 'precipitation' ? 'mm' : '';
        return "Current {$this->type} level ({$this->value}{$unit}) has exceeded your set threshold of {$this->threshold}{$unit} in {$this->cityName}, {$this->countryCode}.";
    }
}