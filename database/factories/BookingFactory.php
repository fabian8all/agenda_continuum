<?php

namespace Database\Factories;

use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        // Respeta el rango de anticipación de negocio: entre 2 horas y 15 días (ver arquitectura.md).
        $start = Carbon::instance(fake()->dateTimeBetween('+3 hours', '+14 days'))
            ->setTime(fake()->numberBetween(7, 20), 0);
        $end = $start->copy()->addHour();

        return [
            'space_id' => Space::factory(),
            'user_id' => User::factory(),
            'date' => $start->toDateString(),
            'start_time' => $start->toTimeString(),
            'end_time' => $end->toTimeString(),
            'subject' => ucfirst(fake()->words(3, true)),
            'status' => 'confirmada',
        ];
    }

    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pendiente']);
    }

    public function cancelada(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'cancelada']);
    }
}
