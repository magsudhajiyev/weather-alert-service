<!-- resources/views/livewire/weather-dashboard.blade.php -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- City Selection -->
                <div class="mb-6">
                    <label for="city" class="block text-sm font-medium text-gray-700">Select a City</label>
                    <select wire:model.live="selectedCity" id="city" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Choose a city</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->country_code }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Weather Data Display -->
                @if($weatherData)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Current Weather in {{ $weatherData['city_name'] }}
                            </h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Precipitation
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $weatherData['precipitation'] }} mm
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        UV Index
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $weatherData['uv_index'] }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Enable Alerts Button -->
                    <button wire:click="enableAlerts" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                        Enable Weather Alerts
                    </button>
                @endif

                <!-- Error Message -->
                @if($error)
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $error }}</span>
                    </div>
                @endif

                <!-- Success Message -->
                @if(session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>