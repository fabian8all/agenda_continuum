<?php

namespace Database\Factories;

use App\Models\Scenario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 day', '+14 days');
        $end = (clone $start)->modify('+2 hours');

        return [
            'scenario_id' => Scenario::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'pending',
            'title' => ucfirst(fake()->words(3, true)),
            'description' => fake()->sentence(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'approved']);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'rejected']);
    }

    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'canceled']);
    }
}
