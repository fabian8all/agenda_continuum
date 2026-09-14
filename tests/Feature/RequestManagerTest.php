<?php

namespace Tests\Feature;

use App\Http\Livewire\RequestManager;
use App\Models\Event;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class RequestManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 'coordinador' (Administrador General) ve todos los escenarios;
        // el filtrado por escenario de 'admin' se cubre en
        // RequestManagerRoleTest.
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));
    }

    public function test_it_lists_pending_events(): void
    {
        $scenario = Scenario::factory()->create();
        $pending = Event::factory()->for($scenario)->create(['status' => 'pending']);
        Event::factory()->for($scenario)->approved()->create();

        Livewire::test(RequestManager::class)
            ->assertSet('pendingEvents', fn ($events) => $events->pluck('id')->contains($pending->id) && $events->count() === 1);
    }

    public function test_approving_a_pending_event_marks_it_approved(): void
    {
        $event = Event::factory()->for(Scenario::factory())->create(['status' => 'pending']);

        Livewire::test(RequestManager::class)
            ->call('approve', $event->id)
            ->assertSet('successMessage', 'Solicitud aprobada.');

        $this->assertSame('approved', $event->fresh()->status);
    }

    public function test_rejecting_a_pending_event_marks_it_rejected(): void
    {
        $event = Event::factory()->for(Scenario::factory())->create(['status' => 'pending']);

        Livewire::test(RequestManager::class)
            ->call('reject', $event->id)
            ->assertSet('successMessage', 'Solicitud rechazada.');

        $this->assertSame('rejected', $event->fresh()->status);
    }

    public function test_approving_a_non_pending_event_is_rejected(): void
    {
        $event = Event::factory()->for(Scenario::factory())->approved()->create();

        Livewire::test(RequestManager::class)
            ->call('approve', $event->id)
            ->assertSet('errorMessage', 'Esa solicitud ya no está disponible para esta acción.');

        $this->assertSame('approved', $event->fresh()->status);
    }

    public function test_it_lists_approved_events_whose_end_time_already_passed_as_closable(): void
    {
        $scenario = Scenario::factory()->create();
        $past = Event::factory()->for($scenario)->create([
            'status' => 'approved',
            'start_time' => Carbon::now()->subDays(2),
            'end_time' => Carbon::now()->subDay(),
        ]);
        Event::factory()->for($scenario)->approved()->create([
            'start_time' => Carbon::now()->addDay(),
            'end_time' => Carbon::now()->addDay()->addHours(2),
        ]);

        Livewire::test(RequestManager::class)
            ->assertSet('closableEvents', fn ($events) => $events->pluck('id')->contains($past->id) && $events->count() === 1);
    }

    public function test_completing_a_past_approved_event_marks_it_completed(): void
    {
        $event = Event::factory()->for(Scenario::factory())->create([
            'status' => 'approved',
            'start_time' => Carbon::now()->subDays(2),
            'end_time' => Carbon::now()->subDay(),
        ]);

        Livewire::test(RequestManager::class)
            ->call('complete', $event->id)
            ->assertSet('successMessage', 'Evento marcado como terminado.');

        $this->assertSame('completed', $event->fresh()->status);
    }

    public function test_canceling_a_past_approved_event_marks_it_canceled(): void
    {
        $event = Event::factory()->for(Scenario::factory())->create([
            'status' => 'approved',
            'start_time' => Carbon::now()->subDays(2),
            'end_time' => Carbon::now()->subDay(),
        ]);

        Livewire::test(RequestManager::class)
            ->call('cancel', $event->id)
            ->assertSet('successMessage', 'Evento cancelado.');

        $this->assertSame('canceled', $event->fresh()->status);
    }

    public function test_completing_a_future_approved_event_is_rejected(): void
    {
        $event = Event::factory()->for(Scenario::factory())->approved()->create([
            'start_time' => Carbon::now()->addDay(),
            'end_time' => Carbon::now()->addDay()->addHours(2),
        ]);

        Livewire::test(RequestManager::class)
            ->call('complete', $event->id)
            ->assertSet('errorMessage', 'Esa solicitud ya no está disponible para esta acción.');

        $this->assertSame('approved', $event->fresh()->status);
    }

    public function test_completing_a_pending_event_is_rejected(): void
    {
        $event = Event::factory()->for(Scenario::factory())->create(['status' => 'pending']);

        Livewire::test(RequestManager::class)
            ->call('complete', $event->id)
            ->assertSet('errorMessage', 'Esa solicitud ya no está disponible para esta acción.');

        $this->assertSame('pending', $event->fresh()->status);
    }
}
