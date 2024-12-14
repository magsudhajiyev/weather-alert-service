<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Weather\WeatherAlertService;

class CheckWeatherConditions extends Command
{
    protected $signature = 'weather:check';
    protected $description = 'Check weather conditions and send alerts if necessary';

    public function handle(WeatherAlertService $weatherAlertService)
    {
        $this->info('Checking weather conditions...');
        
        try {
            $weatherAlertService->processAlerts();
            $this->info('Weather check completed successfully.');
        } catch (\Exception $e) {
            $this->error('Error checking weather conditions: ' . $e->getMessage());
        }
    }
}