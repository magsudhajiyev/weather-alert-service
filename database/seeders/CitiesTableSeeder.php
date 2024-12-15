<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            // Europe
            [
                'name' => 'London',
                'country_code' => 'GB',
                'latitude' => 51.5074,
                'longitude' => -0.1278,
            ],
            [
                'name' => 'Paris',
                'country_code' => 'FR',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
            ],
            [
                'name' => 'Berlin',
                'country_code' => 'DE',
                'latitude' => 52.5200,
                'longitude' => 13.4050,
            ],
            [
                'name' => 'Rome',
                'country_code' => 'IT',
                'latitude' => 41.9028,
                'longitude' => 12.4964,
            ],
            [
                'name' => 'Madrid',
                'country_code' => 'ES',
                'latitude' => 40.4168,
                'longitude' => -3.7038,
            ],

            // North America
            [
                'name' => 'New York',
                'country_code' => 'US',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
            ],
            [
                'name' => 'Los Angeles',
                'country_code' => 'US',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
            ],
            [
                'name' => 'Toronto',
                'country_code' => 'CA',
                'latitude' => 43.6532,
                'longitude' => -79.3832,
            ],
            [
                'name' => 'Mexico City',
                'country_code' => 'MX',
                'latitude' => 19.4326,
                'longitude' => -99.1332,
            ],

            // Asia
            [
                'name' => 'Tokyo',
                'country_code' => 'JP',
                'latitude' => 35.6762,
                'longitude' => 139.6503,
            ],
            [
                'name' => 'Shanghai',
                'country_code' => 'CN',
                'latitude' => 31.2304,
                'longitude' => 121.4737,
            ],
            [
                'name' => 'Singapore',
                'country_code' => 'SG',
                'latitude' => 1.3521,
                'longitude' => 103.8198,
            ],
            [
                'name' => 'Dubai',
                'country_code' => 'AE',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
            ],
            [
                'name' => 'Mumbai',
                'country_code' => 'IN',
                'latitude' => 19.0760,
                'longitude' => 72.8777,
            ],

            // South America
            [
                'name' => 'São Paulo',
                'country_code' => 'BR',
                'latitude' => -23.5505,
                'longitude' => -46.6333,
            ],
            [
                'name' => 'Buenos Aires',
                'country_code' => 'AR',
                'latitude' => -34.6037,
                'longitude' => -58.3816,
            ],
            [
                'name' => 'Rio de Janeiro',
                'country_code' => 'BR',
                'latitude' => -22.9068,
                'longitude' => -43.1729,
            ],

            // Africa
            [
                'name' => 'Cape Town',
                'country_code' => 'ZA',
                'latitude' => -33.9249,
                'longitude' => 18.4241,
            ],
            [
                'name' => 'Cairo',
                'country_code' => 'EG',
                'latitude' => 30.0444,
                'longitude' => 31.2357,
            ],
            [
                'name' => 'Lagos',
                'country_code' => 'NG',
                'latitude' => 6.5244,
                'longitude' => 3.3792,
            ],

            // Oceania
            [
                'name' => 'Sydney',
                'country_code' => 'AU',
                'latitude' => -33.8688,
                'longitude' => 151.2093,
            ],
            [
                'name' => 'Melbourne',
                'country_code' => 'AU',
                'latitude' => -37.8136,
                'longitude' => 144.9631,
            ],
            [
                'name' => 'Auckland',
                'country_code' => 'NZ',
                'latitude' => -36.8509,
                'longitude' => 174.7645,
            ],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['name' => $city['name'], 'country_code' => $city['country_code']],
                $city
            );
        }
    }
}