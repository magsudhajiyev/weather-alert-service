<?php

namespace Tests\Unit\Console\Commands;

use Tests\Unit\BaseServiceTestCase;
use App\Services\Weather\WeatherAlertService;
use Illuminate\Support\Facades\Log;
use Mockery;

class CheckWeatherConditionsTest extends BaseServiceTestCase
{
    private $mockWeatherAlertService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockWeatherAlertService = Mockery::mock(WeatherAlertService::class);
        $this->app->instance(WeatherAlertService::class, $this->mockWeatherAlertService);
        
        Log::spy();
    }

    public function test_command_processes_alerts_successfully(): void
    {
        $this->mockWeatherAlertService
            ->shouldReceive('processAlerts')
            ->once();

        $this->artisan('weather:check')
            ->expectsOutput('Checking weather conditions...')
            ->expectsOutput('Weather check completed successfully.')
            ->assertSuccessful();

        Log::shouldNotHaveReceived('error');
    }

    public function test_command_handles_errors_gracefully(): void
    {
        $this->mockWeatherAlertService
            ->shouldReceive('processAlerts')
            ->once()
            ->andThrow(new \Exception('Test error'));

        $this->artisan('weather:check')
            ->expectsOutput('Checking weather conditions...')
            ->expectsOutput('Error checking weather conditions: Test error')
            ->assertFailed();

        Log::shouldHaveReceived('error')
            ->with('Error in weather:check command', 
                Mockery::on(function ($args) {
                    return $args['error'] === 'Test error' 
                        && !empty($args['trace']);
                })
            );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}