<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpaceBookingModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_role_and_bookings_relation(): void
    {
        $user = User::factory()->create(['role' => 'docente']);
        $booking = Booking::factory()->for($user)->create();

        $this->assertSame('docente', $user->role);
        $this->assertTrue($user->bookings->contains($booking));
    }

    public function test_space_and_booking_relations_resolve(): void
    {
        $space = Space::factory()->create();
        $booking = Booking::factory()->for($space)->create();

        $this->assertTrue($space->bookings->contains($booking));
        $this->assertTrue($booking->space->is($space));
        $this->assertTrue($booking->user->is($booking->user));
    }

    public function test_space_is_not_available_when_time_ranges_overlap(): void
    {
        $space = Space::factory()->create();

        Booking::factory()->for($space)->create([
            'date' => '2026-10-01',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'status' => 'confirmada',
        ]);

        $this->assertFalse($space->isAvailable('2026-10-01', '11:00:00', '13:00:00'));
    }

    public function test_space_is_available_when_time_ranges_do_not_overlap(): void
    {
        $space = Space::factory()->create();

        Booking::factory()->for($space)->create([
            'date' => '2026-10-01',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'status' => 'confirmada',
        ]);

        $this->assertTrue($space->isAvailable('2026-10-01', '12:00:00', '13:00:00'));
        $this->assertTrue($space->isAvailable('2026-10-02', '10:00:00', '12:00:00'));
    }

    public function test_cancelled_bookings_do_not_block_availability(): void
    {
        $space = Space::factory()->create();

        Booking::factory()->for($space)->cancelada()->create([
            'date' => '2026-10-01',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
        ]);

        $this->assertTrue($space->isAvailable('2026-10-01', '10:00:00', '12:00:00'));
    }

    public function test_a_booking_can_exclude_itself_from_the_overlap_check(): void
    {
        $space = Space::factory()->create();

        $booking = Booking::factory()->for($space)->create([
            'date' => '2026-10-01',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'status' => 'confirmada',
        ]);

        $this->assertFalse($space->isAvailable('2026-10-01', '10:00:00', '12:00:00'));
        $this->assertTrue($space->isAvailable('2026-10-01', '10:00:00', '12:00:00', $booking->id));
    }
}
