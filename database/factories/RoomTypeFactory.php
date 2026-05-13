<?php

namespace Database\Factories;

use App\Modules\Hotel\Domain\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model;

/**
 * @extends Factory<Model>
 */
class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
