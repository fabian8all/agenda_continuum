<?php

namespace Tests\Feature;

use App\Http\Livewire\ReservationForm;
use App\Models\Event;
use App\Models\Scenario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_request_creates_a_pending_event(): void
    {
        $scenario = Scenario::factory()->create();
        $start = Carbon::now()->addDay()->setTime(10, 0);
        $end = $start->copy()->addHours(2);

        Livewire::test(ReservationForm::class)
            ->set('scenario_id', $scenario->id)
            ->set('start_time', $start->format('Y-m-d\TH:i'))
            ->set('end_time', $end->format('Y-m-d\TH:i'))
            ->set('description', 'Clase de laboratorio')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('successMessage', 'Tu solicitud fue registrada y quedó pendiente de aprobación.');

        $this->assertDatabaseHas('events', [
            'scenario_id' => $scenario->id,
            'status' => 'pending',
            'description' => 'Clase de laboratorio',
        ]);
    }

    public function test_the_form_resets_after_a_successful_submission(): void
    {
        $scenario = Scenario::factory()->create();
        $start = Carbon::now()->addDay()->setTime(10, 0);
        $end = $start->copy()->addHours(2);

        Livewire::test(ReservationForm::class)
            ->set('scenario_id', $scenario->id)
            ->set('start_time', $start->format('Y-m-d\TH:i'))
            ->set('end_time', $end->format('Y-m-d\TH:i'))
            ->call('submit')
            ->assertSet('scenario_id', '')
            ->assertSet('start_time', '')
            ->assertSet('end_time', '');
    }

    public function test_scenario_start_and_end_time_are_required(): void
    {
        Livewire::test(ReservationForm::class)
            ->call('submit')
            ->assertHasErrors(['scenario_id' => 'required', 'start_time' => 'required', 'end_time' => 'required']);

        $this->assertSame(0, Event::count());
    }

    public function test_end_time_must_be_after_start_time(): void
    {
        $scenario = Scenario::factory()->create();
        $start = Carbon::now()->addDay()->setTime(10, 0);

        Livewire::test(ReservationForm::class)
            ->set('scenario_id', $scenario->id)
            ->set('start_time', $start->format('Y-m-d\TH:i'))
            ->set('end_time', $start->copy()->subHour()->format('Y-m-d\TH:i'))
            ->call('submit')
            ->assertHasErrors(['end_time' => 'after']);

        $this->assertSame(0, Event::count());
    }

    public function test_start_time_cannot_be_in_the_past(): void
    {
        $scenario = Scenario::factory()->create();
        $start = Carbon::now()->subDay();

        Livewire::test(ReservationForm::class)
            ->set('scenario_id', $scenario->id)
            ->set('start_time', $start->format('Y-m-d\TH:i'))
            ->set('end_time', $start->copy()->addHours(2)->format('Y-m-d\TH:i'))
            ->call('submit')
            ->assertHasErrors(['start_time' => 'after']);

        $this->assertSame(0, Event::count());
    }

    public function test_an_overlapping_pending_or_approved_event_blocks_the_request(): void
    {
        $scenario = Scenario::factory()->create();
        $existingStart = Carbon::now()->addDay()->setTime(10, 0);
        $existingEnd = $existingStart->copy()->addHours(2);

        Event::factory()->for($scenario)->approved()->create([
            'start_time' => $existingStart,
            'end_time' => $existingEnd,
        ]);

        Livewire::test(ReservationForm::class)
            ->set('scenario_id', $scenario->id)
            ->set('start_time', $existingStart->copy()->addHour()->format('Y-m-d\TH:i'))
            ->set('end_time', $existingEnd->copy()->addHour()->format('Y-m-d\TH:i'))
            ->call('submit')
            ->assertHasErrors('start_time');

        $this->assertSame(1, Event::count());
    }

    public function test_a_request_for_a_different_scenario_at_the_same_time_is_allowed(): void
    {
        $scenarioA = Scenario::factory()->create();
        $scenarioB = Scenario::factory()->create();
        $start = Carbon::now()->addDay()->setTime(10, 0);
        $end = $start->copy()->addHours(2);

        Event::factory()->for($scenarioA)->approved()->create([
            'start_time' => $start,
            'end_time' => $end,
        ]);

        Livewire::test(ReservationForm::class)
            ->set('scenario_id', $scenarioB->id)
            ->set('start_time', $start->format('Y-m-d\TH:i'))
            ->set('end_time', $end->format('Y-m-d\TH:i'))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertSame(2, Event::count());
    }

    public function test_rejected_and_canceled_events_do_not_block_availability(): void
    {
        $scenario = Scenario::factory()->create();
        $start = Carbon::now()->addDay()->setTime(10, 0);
        $end = $start->copy()->addHours(2);

        Event::factory()->for($scenario)->rejected()->create([
            'start_time' => $start,
            'end_time' => $end,
        ]);
        Event::factory()->for($scenario)->canceled()->create([
            'start_time' => $start,
            'end_time' => $end,
        ]);

        Livewire::test(ReservationForm::class)
            ->set('scenario_id', $scenario->id)
            ->set('start_time', $start->format('Y-m-d\TH:i'))
            ->set('end_time', $end->format('Y-m-d\TH:i'))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertSame(3, Event::count());
    }
}
