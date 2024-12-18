<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Weather\WeatherAlertService;
use Illuminate\Support\Facades\Log;

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
            Log::error('Error in weather:check command', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->error('Error checking weather conditions: ' . $e->getMessage());
        }
    }
}