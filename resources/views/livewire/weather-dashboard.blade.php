<!-- resources/views/livewire/weather-dashboard.blade.php -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Active Alert Settings -->
                @if($alertSettings->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Active Alerts</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @foreach($alertSettings as $setting)
                                    <li class="px-4 py-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">{{ $setting->city->name }}, {{ $setting->city->country_code }}</h4>
                                                <p class="text-sm text-gray-500">
                                                    Precipitation: {{ $setting->precipitation_threshold }}mm | 
                                                    UV Index: {{ $setting->uv_index_threshold }}
                                                </p>
                                            </div>
                                            <button wire:click="deleteAlertSetting({{ $setting->id }})" 
                                                    class="text-red-600 hover:text-red-900">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Add New Alert -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Alert</h3>
                    
                    <!-- City Selection -->
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">Select City</label>
                        <select wire:model.live="selectedCity" id="city" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Choose a city</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->country_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($selectedCity)
                        <!-- Threshold Settings -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="precipitation" class="block text-sm font-medium text-gray-700">Precipitation Threshold (mm)</label>
                                <input type="number" 
                                       id="precipitation" 
                                       wire:model="precipitationThreshold" 
                                       step="0.1" 
                                       min="0" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="uv" class="block text-sm font-medium text-gray-700">UV Index Threshold</label>
                                <input type="number" 
                                       id="uv" 
                                       wire:model="uvThreshold" 
                                       step="0.1" 
                                       min="0" 
                                       max="11" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Weather Data Display -->
                        @if($weatherData)
                            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Current Weather in {{ $weatherData['city_name'] }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ ucfirst($weatherData['description']) }}
                                    </p>
                                    <dl class="mt-4 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Precipitation</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($weatherData['precipitation'], 2) }} mm</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">UV Index</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($weatherData['uv_index'], 1) }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        @endif

                        <!-- Enable Alerts Button -->
                        <div class="mt-6">
                            <button wire:click="enableAlerts" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                Enable Weather Alerts
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Messages -->
                @if($error)
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $error }}</span>
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>