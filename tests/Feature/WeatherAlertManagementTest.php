<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\City;
use App\Models\AlertSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\WeatherDashboard;
use App\Contracts\Services\WeatherServiceInterface;
use App\DTOs\WeatherData;
use Mockery;

class WeatherAlertManagementTest extends TestCase
{
    use RefreshDatabase;

    private $mockWeatherService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockWeatherService = $this->mockWeatherService();
    }

    public function test_user_can_see_weather_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/weather');

        $response->assertStatus(200)
            ->assertSeeLivewire('weather-dashboard');
    }

    public function test_user_can_create_alert_setting(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create([
            'name' => 'Test City',
            'country_code' => 'TC'
        ]);

        Livewire::actingAs($user)
            ->test(WeatherDashboard::class)
            ->set('selectedCity', $city->id)
            ->set('precipitationThreshold', 30)
            ->set('uvThreshold', 5)
            ->call('enableAlerts');

        $this->assertDatabaseHas('alert_settings', [
            'user_id' => $user->id,
            'city_id' => $city->id,
            'precipitation_threshold' => 30,
            'uv_index_threshold' => 5,
        ]);
    }

    public function test_user_can_delete_alert_setting(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create();
        $alertSetting = AlertSetting::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id,
        ]);

        Livewire::actingAs($user)
            ->test(WeatherDashboard::class)
            ->call('deleteAlertSetting', $alertSetting->id);

        $this->assertDatabaseMissing('alert_settings', [
            'id' => $alertSetting->id
        ]);
    }

    public function test_user_cannot_create_duplicate_city_alerts(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create([
            'name' => 'Test City',
            'country_code' => 'TC'
        ]);
        
        // Create first alert setting
        AlertSetting::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id,
        ]);

        // Try to create duplicate
        Livewire::actingAs($user)
            ->test(WeatherDashboard::class)
            ->set('selectedCity', $city->id)
            ->set('precipitationThreshold', 30)
            ->set('uvThreshold', 5)
            ->call('enableAlerts')
            ->assertSet('error', 'Alert settings already exist for this city.');
    }

    public function test_weather_data_loads_when_city_selected(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create([
            'name' => 'Test City',
            'country_code' => 'TC'
        ]);

        $weatherData = new WeatherData(
            precipitation: 0.5,
            uvIndex: 4.0,
            cityName: $city->name,
            countryCode: $city->country_code,
            description: 'Clear'
        );

        $this->mockWeatherService
            ->shouldReceive('getCurrentWeather')
            ->with(Mockery::type(City::class))
            ->andReturn($weatherData);

        Livewire::actingAs($user)
            ->test(WeatherDashboard::class)
            ->set('selectedCity', $city->id)
            ->assertSet('weatherData.city_name', 'Test City')
            ->assertSet('weatherData.precipitation', 0.5)
            ->assertSet('weatherData.uv_index', 4.0);
    }

    private function mockWeatherService(): \Mockery\MockInterface
    {
        $mock = Mockery::mock(WeatherServiceInterface::class);
        $this->app->instance(WeatherServiceInterface::class, $mock);
        return $mock;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}