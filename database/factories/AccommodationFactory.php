<?php

namespace Database\Factories;

use App\Model;
use App\Modules\Hotel\Infrastructure\Models\Accommodation;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends Factory<Model>
 */
class AccommodationFactory extends Factory
{
    protected $model = Accommodation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
