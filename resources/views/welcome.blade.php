<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Weather Alerts') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="relative min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-indigo-600">Weather Alerts</h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 mr-4">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Stay Informed About Weather Conditions
                    </h2>
                    <p class="mt-4 text-xl text-gray-500">
                        Get personalized alerts for precipitation and UV index levels in your chosen cities.
                    </p>
                </div>

                <!-- Features -->
                <div class="mt-16">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Feature 1 -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900">Custom Thresholds</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Set your own alert thresholds for precipitation and UV index levels.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900">Multiple Cities</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Monitor weather conditions in multiple cities simultaneously.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900">Email Notifications</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Receive email alerts when conditions exceed your thresholds.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>