<?php

namespace App\Repositories;

use App\Contracts\Repositories\AlertSettingsRepositoryInterface;
use App\Models\User;
use App\Models\City;
use App\Models\AlertSetting;
use Illuminate\Support\Collection;

class AlertSettingsRepository implements AlertSettingsRepositoryInterface
{
    public function getForUserAndCity(User $user, City $city): ?AlertSetting
    {
        return AlertSetting::where('user_id', $user->id)
            ->where('city_id', $city->id)
            ->first();
    }

    public function getActiveSettings(): Collection
    {
        return AlertSetting::where('is_active', true)
            ->with(['user', 'city'])
            ->get();
    }

    public function createOrUpdate(User $user, City $city, array $data): AlertSetting
    {
        return AlertSetting::updateOrCreate(
            [
                'user_id' => $user->id,
                'city_id' => $city->id,
            ],
            $data
        );
    }
}