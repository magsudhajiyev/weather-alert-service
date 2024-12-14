<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use App\Models\City;
use App\Models\WeatherAlert;
use Carbon\Carbon;

interface WeatherAlertRepositoryInterface
{
    public function create(array $data): WeatherAlert;
    public function getLastAlertForUserAndCity(User $user, City $city, string $type): ?WeatherAlert;
    public function hasRecentAlert(User $user, City $city, string $type, Carbon $since): bool;
}