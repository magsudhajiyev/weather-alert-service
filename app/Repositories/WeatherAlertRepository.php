<?php

namespace App\Repositories;

use App\Contracts\Repositories\WeatherAlertRepositoryInterface;
use App\Models\User;
use App\Models\City;
use App\Models\WeatherAlert;
use Carbon\Carbon;

class WeatherAlertRepository implements WeatherAlertRepositoryInterface
{
    public function create(array $data): WeatherAlert
    {
        return WeatherAlert::create($data);
    }

    public function getLastAlertForUserAndCity(User $user, City $city, string $type): ?WeatherAlert
    {
        return WeatherAlert::where('user_id', $user->id)
            ->where('city_id', $city->id)
            ->where('type', $type)
            ->latest()
            ->first();
    }

    public function hasRecentAlert(User $user, City $city, string $type, Carbon $since): bool
    {
        return WeatherAlert::where('user_id', $user->id)
            ->where('city_id', $city->id)
            ->where('type', $type)
            ->where('created_at', '>=', $since)
            ->exists();
    }
}