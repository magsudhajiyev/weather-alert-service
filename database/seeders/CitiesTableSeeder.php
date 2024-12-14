<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            [
                'name' => 'London',
                'country_code' => 'GB',
                'latitude' => 51.5074,
                'longitude' => -0.1278,
            ],
            [
                'name' => 'New York',
                'country_code' => 'US',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
            ],
            [
                'name' => 'Tokyo',
                'country_code' => 'JP',
                'latitude' => 35.6762,
                'longitude' => 139.6503,
            ],
            [
                'name' => 'Paris',
                'country_code' => 'FR',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
            ],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['name' => $city['name']],
                $city
            );
        }
    }
}