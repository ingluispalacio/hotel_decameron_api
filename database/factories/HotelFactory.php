<?php

namespace Database\Factories;

use App\Model;
use App\Modules\Hotel\Domain\Models\Hotel;
use App\Modules\Hotel\Domain\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),

            'address' => fake()->address(),

            'nit' => fake()->unique()->numerify('########'),

            'max_rooms' => fake()->numberBetween(10, 200),

            'city_id' => City::factory(),
        ];
    }
}
