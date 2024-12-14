<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\AlertSetting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WeatherDashboard extends Component
{
    public $selectedCity = '';
    public $cities = [];
    public $weatherData = null;
    public $error = '';

    protected $rules = [
        'selectedCity' => 'required|exists:cities,id',
    ];

    public function mount()
    {
        $this->cities = City::orderBy('name')->get();
    }

    public function updatedSelectedCity($value)
    {
        if ($value) {
            $this->loadWeatherData();
        }
    }

    public function loadWeatherData()
    {
        try {
            $city = City::findOrFail($this->selectedCity);
            
            // Here we'll integrate with weather API
            // For now, using placeholder data
            $this->weatherData = [
                'precipitation' => 25.5,
                'uv_index' => 7.2,
                'city_name' => $city->name,
            ];

            $this->error = '';
        } catch (\Exception $e) {
            $this->error = 'Unable to load weather data. Please try again.';
            $this->weatherData = null;
        }
    }

    public function enableAlerts()
    {
        $this->validate();

        $user = Auth::user();
        
        // Create or update alert settings
        AlertSetting::updateOrCreate(
            [
                'user_id' => $user->id,
                'city_id' => $this->selectedCity,
            ],
            [
                'precipitation_threshold' => 25.0, // Default threshold
                'uv_index_threshold' => 6.0,      // Default threshold
                'is_active' => true,
            ]
        );

        session()->flash('message', 'Weather alerts enabled successfully!');
    }

    public function render()
    {
        return view('livewire.weather-dashboard');
    }
}