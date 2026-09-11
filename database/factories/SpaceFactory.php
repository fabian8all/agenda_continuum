<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Space>
 */
class SpaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('?-###')),
            'name' => ucfirst(fake()->words(2, true)),
            'type' => fake()->randomElement(['aula_teorica', 'laboratorio', 'auditorio']),
            'capacity' => fake()->numberBetween(10, 120),
            'location' => 'Edificio '.fake()->randomLetter().' - Piso '.fake()->numberBetween(1, 4),
            'is_active' => true,
        ];
    }
}
