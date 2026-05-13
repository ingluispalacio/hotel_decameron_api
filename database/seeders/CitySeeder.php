<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Modules\Hotel\Domain\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Cartagena',
            'Santa Marta',
            'Barranquilla',
        ];

        foreach ($cities as $city) {

            City::firstOrCreate([
                'name' => $city,
            ]);
        }
    }
}