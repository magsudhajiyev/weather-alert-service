<?php

namespace Tests\Unit\Services;

use Tests\Unit\BaseServiceTestCase;
use App\Services\Weather\WeatherAlertService;
use App\Contracts\Services\WeatherServiceInterface;
use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use App\Models\{User, City, AlertSetting};
use App\DTOs\WeatherData;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeatherAlert;
use Mockery;
use Mockery\MockInterface;

class WeatherAlertServiceTest extends BaseServiceTestCase
{
    private WeatherAlertService $alertService;
    private WeatherServiceInterface&MockInterface $mockWeatherService;
    private AlertSettingsRepositoryInterface&MockInterface $mockAlertSettingsRepo;

    protected function setUp(): void
    {
        parent::setUp();
        
        Mail::fake();
        
        $this->mockWeatherService = Mockery::mock(WeatherServiceInterface::class);
        $this->mockAlertSettingsRepo = Mockery::mock(AlertSettingsRepositoryInterface::class);
        
        $this->alertService = new WeatherAlertService(
            $this->mockWeatherService,
            $this->mockAlertSettingsRepo
        );
    }

    public function test_processes_alerts_and_sends_notifications_when_thresholds_exceeded(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
        
        $city = City::factory()->create([
            'name' => 'Test City',
            'country_code' => 'TC'
        ]);

        $alertSetting = AlertSetting::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id,
            'precipitation_threshold' => 3.0,
            'uv_index_threshold' => 6.0,
            'is_active' => true
        ]);

        $weatherData = new WeatherData(
            precipitation: 5.0,
            uvIndex: 8.0,
            cityName: $city->name,
            countryCode: $city->country_code,
            description: 'Heavy rain with high UV'
        );

        $this->mockAlertSettingsRepo
            ->shouldReceive('getActiveSettings')
            ->once()
            ->andReturn(collect([$alertSetting]));

        $this->mockWeatherService
            ->shouldReceive('getCurrentWeather')
            ->with(Mockery::type(City::class))
            ->once()
            ->andReturn($weatherData);

        $this->mockWeatherService
            ->shouldReceive('shouldSendPrecipitationAlert')
            ->with(5.0, 3.0)
            ->once()
            ->andReturn(true);

        $this->mockWeatherService
            ->shouldReceive('shouldSendUVAlert')
            ->with(8.0, 6.0)
            ->once()
            ->andReturn(true);

        $this->alertService->processAlerts();

        Mail::assertSent(WeatherAlert::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_does_not_send_alerts_when_thresholds_not_exceeded(): void
    {
        // Arrange
        $user = User::factory()->create();
        $city = City::factory()->create();
        $alertSetting = AlertSetting::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id,
            'precipitation_threshold' => 10.0,
            'uv_index_threshold' => 10.0,
            'is_active' => true
        ]);

        $weatherData = new WeatherData(
            precipitation: 5.0,
            uvIndex: 5.0,
            cityName: $city->name,
            countryCode: $city->country_code,
            description: 'Moderate conditions'
        );

        $this->mockAlertSettingsRepo
            ->shouldReceive('getActiveSettings')
            ->once()
            ->andReturn(collect([$alertSetting]));

        $this->mockWeatherService
            ->shouldReceive('getCurrentWeather')
            ->with(Mockery::type(City::class))
            ->once()
            ->andReturn($weatherData);

        $this->mockWeatherService
            ->shouldReceive('shouldSendPrecipitationAlert')
            ->with(5.0, 10.0)
            ->once()
            ->andReturn(false);

        $this->mockWeatherService
            ->shouldReceive('shouldSendUVAlert')
            ->with(5.0, 10.0)
            ->once()
            ->andReturn(false);

        $this->alertService->processAlerts();

        Mail::assertNothingSent();
    }

    public function test_handles_inactive_alert_settings(): void
    {
        $alertSetting = AlertSetting::factory()->create(['is_active' => false]);

        $this->mockAlertSettingsRepo
            ->shouldReceive('getActiveSettings')
            ->once()
            ->andReturn(collect([]));

        $this->alertService->processAlerts();

        Mail::assertNothingSent();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}