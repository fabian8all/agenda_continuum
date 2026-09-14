<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scenario>
 */
class ScenarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->words(2, true)),
            'capacity' => fake()->numberBetween(10, 120),
            'resources' => ['projector' => true],
            'admin_id' => null,
        ];
    }
}
