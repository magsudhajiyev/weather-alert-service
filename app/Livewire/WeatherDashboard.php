<?php

namespace App\Livewire;

use App\Models\City;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Services\WeatherServiceInterface;
use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use Illuminate\Support\Facades\Log;

class WeatherDashboard extends Component
{
    public $selectedCity = '';
    public $cities = [];
    public $weatherData = [];
    public $error = '';
    
    private WeatherServiceInterface $weatherService;
    private AlertSettingsRepositoryInterface $alertSettingsRepository;

    public function boot(WeatherServiceInterface $weatherService, AlertSettingsRepositoryInterface $alertSettingsRepository)
    {
        $this->weatherService = $weatherService;
        $this->alertSettingsRepository = $alertSettingsRepository;
    }

    public function mount()
    {
        $this->cities = City::orderBy('name')->get();
    }

    public function updatedSelectedCity($value)
    {
        if ($value) {
            $this->loadWeatherData();
        } else {
            $this->weatherData = [];
            $this->error = '';
        }
    }

    public function loadWeatherData()
    {
        try {
            $city = City::findOrFail($this->selectedCity);
            $weatherDTO = $this->weatherService->getCurrentWeather($city);
            
            // Convert DTO to array for Livewire
            $this->weatherData = [
                'precipitation' => $weatherDTO->precipitation,
                'uv_index' => $weatherDTO->uvIndex,
                'city_name' => $weatherDTO->cityName,
                'country_code' => $weatherDTO->countryCode,
                'description' => $weatherDTO->description
            ];
            
            Log::info('Weather data loaded successfully', [
                'city' => $city->name,
                'data' => $this->weatherData
            ]);
            
            $this->error = '';
        } catch (\Exception $e) {
            Log::error('Error loading weather data', [
                'error' => $e->getMessage(),
                'city_id' => $this->selectedCity
            ]);
            
            $this->error = 'Unable to load weather data. Please try again.';
            $this->weatherData = [];
        }
    }

    public function enableAlerts()
    {
        $this->validate([
            'selectedCity' => 'required|exists:cities,id',
        ]);

        try {
            $user = Auth::user();
            $city = City::findOrFail($this->selectedCity);
            
            $this->alertSettingsRepository->createOrUpdate($user, $city, [
                'precipitation_threshold' => 0.1, // Default threshold
                'uv_index_threshold' => 0.1,      // Default threshold
                'is_active' => true,
            ]);

            session()->flash('message', 'Weather alerts enabled successfully!');
        } catch (\Exception $e) {
            Log::error('Error enabling alerts', [
                'error' => $e->getMessage(),
                'city_id' => $this->selectedCity,
                'user_id' => Auth::id()
            ]);
            session()->flash('error', 'Failed to enable weather alerts. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.weather-dashboard');
    }
}