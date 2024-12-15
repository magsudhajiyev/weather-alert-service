<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\DTOs\WeatherData;
use App\Models\City;
use App\Models\User;

class WeatherAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly User $user,
        private readonly WeatherData $weatherData,
        private readonly string $alertType,
        private readonly float $threshold
    ) {}

    public function build()
    {
        return $this->markdown('emails.weather-alert')
            ->subject($this->getSubject())
            ->with([
                'userName' => $this->user->name,
                'cityName' => $this->weatherData->cityName,
                'alertType' => $this->alertType,
                'currentValue' => $this->getCurrentValue(),
                'threshold' => $this->threshold,
                'unit' => $this->getUnit(),
                'description' => $this->weatherData->description
            ]);
    }

    private function getSubject(): string
    {
        return "Weather Alert: High {$this->getAlertTypeName()} in {$this->weatherData->cityName}";
    }

    private function getCurrentValue(): float
    {
        return match($this->alertType) {
            'precipitation' => $this->weatherData->precipitation,
            'uv' => $this->weatherData->uvIndex,
            default => 0.0
        };
    }

    private function getUnit(): string
    {
        return match($this->alertType) {
            'precipitation' => 'mm/h',
            'uv' => '',
            default => ''
        };
    }

    private function getAlertTypeName(): string
    {
        return match($this->alertType) {
            'precipitation' => 'Precipitation',
            'uv' => 'UV Index',
            default => $this->alertType
        };
    }
}