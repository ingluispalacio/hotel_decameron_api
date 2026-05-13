<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Modules\Hotel\Domain\Models\RoomType;
use App\Modules\Hotel\Domain\Enums\RoomTypeEnum;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoomTypeEnum::cases() as $roomType) {

            RoomType::firstOrCreate([
                'name' => $roomType->value,
            ]);
        }
    }
}