<?php

namespace App\Livewire;

use App\Models\City;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Services\WeatherServiceInterface;
use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use App\Models\AlertSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class WeatherDashboard extends Component
{
    public $selectedCity = '';
    public $cities = [];
    public $weatherData = [];
    public $error = '';
    public $precipitationThreshold = 25.0;
    public $uvThreshold = 6.0;
    public Collection $alertSettings;
    
    private WeatherServiceInterface $weatherService;
    private AlertSettingsRepositoryInterface $alertSettingsRepository;

    protected $rules = [
        'selectedCity' => 'required|exists:cities,id',
        'precipitationThreshold' => 'required|numeric|min:0',
        'uvThreshold' => 'required|numeric|min:0|max:11',
    ];

    public function boot(
        WeatherServiceInterface $weatherService,
        AlertSettingsRepositoryInterface $alertSettingsRepository
    ) {
        $this->weatherService = $weatherService;
        $this->alertSettingsRepository = $alertSettingsRepository;
    }

    public function mount()
    {
        $this->cities = City::orderBy('name')->get();
        $this->loadAlertSettings();
    }

    public function loadAlertSettings()
    {
        $this->alertSettings = AlertSetting::with('city')
            ->where('user_id', Auth::id())
            ->get();
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
            
            $this->weatherData = [
                'precipitation' => $weatherDTO->precipitation,
                'uv_index' => $weatherDTO->uvIndex,
                'city_name' => $weatherDTO->cityName,
                'country_code' => $weatherDTO->countryCode,
                'description' => $weatherDTO->description
            ];
            
            $this->error = '';
        } catch (\Exception $e) {
            $this->error = 'Unable to load weather data. Please try again.';
            $this->weatherData = [];
            Log::error('Weather data loading error', [
                'error' => $e->getMessage(),
                'city_id' => $this->selectedCity
            ]);
        }
    }

    public function enableAlerts()
    {
        $this->validate();

        try {
            $city = City::findOrFail($this->selectedCity);
            
            // Check if alert setting already exists
            $existingAlert = $this->alertSettings->first(function ($setting) use ($city) {
                return $setting->city_id === $city->id;
            });

            if ($existingAlert) {
                $this->error = 'Alert settings already exist for this city.';
                return;
            }

            $this->alertSettingsRepository->createOrUpdate(
                Auth::user(),
                $city,
                [
                    'precipitation_threshold' => $this->precipitationThreshold,
                    'uv_index_threshold' => $this->uvThreshold,
                    'is_active' => true,
                ]
            );

            session()->flash('message', 'Weather alerts enabled successfully!');
            $this->loadAlertSettings(); // Refresh the list
            $this->resetForm();
        } catch (\Exception $e) {
            $this->error = 'Failed to enable weather alerts. Please try again.';
            Log::error('Error enabling alerts', [
                'error' => $e->getMessage(),
                'city_id' => $this->selectedCity,
                'user_id' => Auth::id()
            ]);
        }
    }

    public function deleteAlertSetting($settingId)
    {
        try {
            $setting = AlertSetting::where('user_id', Auth::id())
                ->where('id', $settingId)
                ->firstOrFail();
                
            $setting->delete();
            
            session()->flash('message', 'Alert settings removed successfully.');
            $this->loadAlertSettings();
        } catch (\Exception $e) {
            $this->error = 'Failed to remove alert settings.';
            Log::error('Error deleting alert setting', [
                'error' => $e->getMessage(),
                'setting_id' => $settingId,
                'user_id' => Auth::id()
            ]);
        }
    }

    private function resetForm()
    {
        $this->selectedCity = '';
        $this->precipitationThreshold = 25.0;
        $this->uvThreshold = 6.0;
        $this->weatherData = [];
    }

    public function render()
    {
        return view('livewire.weather-dashboard');
    }
}