<?php

namespace Database\Factories;

use App\Modules\Auth\Infrastructure\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement(['admin', 'client', 'guest']),
            'description' => $this->faker->sentence(),
        ];
    }
}
