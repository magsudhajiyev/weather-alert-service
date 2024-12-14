<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use App\Models\City;
use App\Models\AlertSetting;
use Illuminate\Support\Collection;

interface AlertSettingsRepositoryInterface
{
    public function getForUserAndCity(User $user, City $city): ?AlertSetting;
    public function getActiveSettings(): Collection;
    public function createOrUpdate(User $user, City $city, array $data): AlertSetting;
}