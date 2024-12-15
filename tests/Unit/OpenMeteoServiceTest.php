<?php

namespace Tests\Unit\Services;

use Tests\Unit\BaseServiceTestCase;
use App\Services\Weather\OpenMeteoService;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use Mockery;

class OpenMeteoServiceTest extends BaseServiceTestCase
{
    private OpenMeteoService $weatherService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->weatherService = new OpenMeteoService();
    }

    public function test_get_current_weather_returns_weather_data(): void
    {
        $city = City::factory()->create([
            'name' => 'London',
            'country_code' => 'GB',
            'latitude' => 51.5074,
            'longitude' => -0.1278
        ]);

        Http::fake([
            'api.open-meteo.com/v1/forecast*' => Http::response([
                'hourly' => [
                    'time' => ['2024-12-14T12:00'],
                    'precipitation' => [1.5],
                    'uv_index' => [5.0]
                ],
                'hourly_units' => [
                    'precipitation' => 'mm',
                    'uv_index' => ''
                ]
            ], 200)
        ]);

        $weatherData = $this->weatherService->getCurrentWeather($city);

        $this->assertEquals(1.5, $weatherData->precipitation);
        $this->assertEquals(5.0, $weatherData->uvIndex);
        $this->assertEquals('London', $weatherData->cityName);
        $this->assertEquals('GB', $weatherData->countryCode);
    }

    public function test_should_send_precipitation_alert(): void
    {
        $this->assertTrue(
            $this->weatherService->shouldSendPrecipitationAlert(5.0, 3.0)
        );

        $this->assertFalse(
            $this->weatherService->shouldSendPrecipitationAlert(2.0, 3.0)
        );
    }

    public function test_should_send_uv_alert(): void
    {
        $this->assertTrue(
            $this->weatherService->shouldSendUVAlert(8.0, 6.0)
        );

        $this->assertFalse(
            $this->weatherService->shouldSendUVAlert(4.0, 6.0)
        );
    }

    public function test_handles_api_error_gracefully(): void
    {
        $city = City::factory()->create();
        
        Http::fake([
            'api.open-meteo.com/v1/forecast*' => Http::response([], 500)
        ]);

        $this->expectException(\Illuminate\Http\Client\RequestException::class);
        
        $this->weatherService->getCurrentWeather($city);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}