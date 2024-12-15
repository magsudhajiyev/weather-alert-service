<?php

namespace Database\Factories;

use App\Models\AlertSetting;
use App\Models\User;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;


class AlertSettingFactory extends Factory
{
    protected $model = AlertSetting::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'city_id' => City::factory(),
            'precipitation_threshold' => $this->faker->randomFloat(2, 0, 50),
            'uv_index_threshold' => $this->faker->randomFloat(2, 0, 11),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}