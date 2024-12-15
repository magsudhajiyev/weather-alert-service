<?php

namespace App\Exceptions;

class WeatherServiceException extends \RuntimeException
{
    public static function apiError(string $message, \Throwable $previous = null): self
    {
        return new self("Weather API error: {$message}", 0, $previous);
    }

    public static function invalidResponse(string $message, \Throwable $previous = null): self
    {
        return new self("Invalid weather data: {$message}", 0, $previous);
    }
}