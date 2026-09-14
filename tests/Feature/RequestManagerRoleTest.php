<?php

namespace Tests\Feature;

use App\Http\Livewire\RequestManager;
use App\Models\Event;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RequestManagerRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_docente_cannot_access_the_request_management_route(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'docente']))
            ->get('/solicitudes')
            ->assertForbidden();
    }

    public function test_an_admin_only_sees_pending_events_for_their_own_scenarios(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ownScenario = Scenario::factory()->create(['admin_id' => $admin->id]);
        $otherScenario = Scenario::factory()->create();

        $ownEvent = Event::factory()->for($ownScenario)->create(['status' => 'pending']);
        Event::factory()->for($otherScenario)->create(['status' => 'pending']);

        $this->actingAs($admin);

        Livewire::test(RequestManager::class)
            ->assertSet('pendingEvents', fn ($events) => $events->pluck('id')->all() === [$ownEvent->id]);
    }

    public function test_an_admin_cannot_approve_an_event_from_another_admins_scenario(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherScenario = Scenario::factory()->create();
        $event = Event::factory()->for($otherScenario)->create(['status' => 'pending']);

        $this->actingAs($admin);

        Livewire::test(RequestManager::class)
            ->call('approve', $event->id)
            ->assertSet('errorMessage', 'Esa solicitud ya no está disponible para esta acción.');

        $this->assertSame('pending', $event->fresh()->status);
    }

    public function test_a_coordinador_sees_pending_events_across_all_scenarios(): void
    {
        $coordinador = User::factory()->create(['role' => 'coordinador']);
        $scenarioA = Scenario::factory()->create();
        $scenarioB = Scenario::factory()->create();

        Event::factory()->for($scenarioA)->create(['status' => 'pending']);
        Event::factory()->for($scenarioB)->create(['status' => 'pending']);

        $this->actingAs($coordinador);

        Livewire::test(RequestManager::class)
            ->assertSet('pendingEvents', fn ($events) => $events->count() === 2);
    }
}
