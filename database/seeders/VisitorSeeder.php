<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Seeder;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        $visitors = [
            [
                'ip_address' => '49.36.0.1',
                'country' => 'India',
                'city' => 'Mumbai',
                'latitude' => 19.0760,
                'longitude' => 72.8777,
            ],
            [
                'ip_address' => '8.8.8.8',
                'country' => 'United States',
                'city' => 'Mountain View',
                'latitude' => 37.3860,
                'longitude' => -122.0838,
            ],
            [
                'ip_address' => '212.58.244.20',
                'country' => 'United Kingdom',
                'city' => 'London',
                'latitude' => 51.5074,
                'longitude' => -0.1278,
            ],
            [
                'ip_address' => '133.242.18.1',
                'country' => 'Japan',
                'city' => 'Tokyo',
                'latitude' => 35.6762,
                'longitude' => 139.6503,
            ],
            [
                'ip_address' => '85.214.132.117',
                'country' => 'Germany',
                'city' => 'Berlin',
                'latitude' => 52.5200,
                'longitude' => 13.4050,
            ],
            [
                'ip_address' => '142.250.190.46',
                'country' => 'Canada',
                'city' => 'Toronto',
                'latitude' => 43.6532,
                'longitude' => -79.3832,
            ],
            [
                'ip_address' => '139.130.4.5',
                'country' => 'Australia',
                'city' => 'Sydney',
                'latitude' => -33.8688,
                'longitude' => 151.2093,
            ],
            [
                'ip_address' => '51.15.0.1',
                'country' => 'France',
                'city' => 'Paris',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
            ],
            [
                'ip_address' => '185.220.101.5',
                'country' => 'United Arab Emirates',
                'city' => 'Dubai',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
            ],
            [
                'ip_address' => '177.12.160.1',
                'country' => 'Brazil',
                'city' => 'Sao Paulo',
                'latitude' => -23.5505,
                'longitude' => -46.6333,
            ],
        ];

        foreach ($visitors as $v) {
            Visitor::create($v);
        }
    }
}
