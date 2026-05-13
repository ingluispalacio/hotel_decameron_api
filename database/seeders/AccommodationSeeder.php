<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Modules\Hotel\Domain\Models\Accommodation;
use App\Modules\Hotel\Domain\Enums\AccommodationEnum;

class AccommodationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (AccommodationEnum::cases() as $accommodation) {

            Accommodation::firstOrCreate([
                'name' => $accommodation->value,
            ]);
        }
    }
}