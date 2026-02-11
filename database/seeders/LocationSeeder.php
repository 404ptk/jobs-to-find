<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['city' => 'Warsaw', 'country' => 'Poland', 'region' => 'Mazovia'],
            ['city' => 'Krakow', 'country' => 'Poland', 'region' => 'Lesser Poland'],
            ['city' => 'Wroclaw', 'country' => 'Poland', 'region' => 'Lower Silesia'],
            ['city' => 'Gdansk', 'country' => 'Poland', 'region' => 'Pomerania'],
            ['city' => 'Poznan', 'country' => 'Poland', 'region' => 'Greater Poland'],
            ['city' => 'Lodz', 'country' => 'Poland', 'region' => 'Lodz Voivodeship'],
            ['city' => 'Remote', 'country' => 'Poland', 'region' => 'Remote'],
        ];

        DB::table('locations')->insert($locations);
    }
}
