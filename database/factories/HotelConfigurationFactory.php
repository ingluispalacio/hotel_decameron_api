<?php

namespace Database\Factories;

use App\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Hotel\Domain\Models\Hotel;
use App\Modules\Hotel\Domain\Models\RoomType;
use App\Modules\Hotel\Domain\Models\Accommodation;
use App\Modules\Hotel\Domain\Models\HotelConfiguration;

/**
 * @extends Factory<Model>
 */
class HotelConfigurationFactory extends Factory
{
    protected $model = HotelConfiguration::class;

    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),

            'room_type_id' => RoomType::factory(),

            'accommodation_id' => Accommodation::factory(),

            'quantity' => fake()->numberBetween(1, 20),
        ];
    }
}
