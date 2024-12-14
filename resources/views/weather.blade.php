<!-- resources/views/weather.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Weather Alerts') }}
        </h2>
    </x-slot>

    <livewire:weather-dashboard />
</x-app-layout>