<?php

namespace Tests\Feature;

use App\Http\Livewire\UserManager;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_docente_cannot_access_the_user_management_route(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'docente']))
            ->get('/administracion/usuarios')
            ->assertForbidden();
    }

    public function test_an_admin_cannot_access_the_user_management_route(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get('/administracion/usuarios')
            ->assertForbidden();
    }

    public function test_a_coordinador_can_access_the_user_management_route(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']))
            ->get('/administracion/usuarios')
            ->assertOk();
    }

    public function test_it_creates_a_user_and_assigns_scenarios_when_role_is_admin(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $scenarioA = Scenario::factory()->create();
        $scenarioB = Scenario::factory()->create();

        Livewire::test(UserManager::class)
            ->set('name', 'Ana Pérez')
            ->set('email', 'ana@ucol.mx')
            ->set('role', 'admin')
            ->set('scenario_ids', [$scenarioA->id, $scenarioB->id])
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('successMessage', 'Usuario creado.');

        $user = User::where('email', 'ana@ucol.mx')->firstOrFail();

        $this->assertSame('admin', $user->role);
        $this->assertSame($user->id, $scenarioA->fresh()->admin_id);
        $this->assertSame($user->id, $scenarioB->fresh()->admin_id);
    }

    public function test_changing_a_users_role_away_from_admin_clears_their_scenario_assignments(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $admin = User::factory()->create(['role' => 'admin']);
        $scenario = Scenario::factory()->create(['admin_id' => $admin->id]);

        Livewire::test(UserManager::class)
            ->call('edit', $admin->id)
            ->set('role', 'docente')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNull($scenario->fresh()->admin_id);
    }

    public function test_editing_a_users_scenarios_replaces_the_previous_assignment(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $admin = User::factory()->create(['role' => 'admin']);
        $oldScenario = Scenario::factory()->create(['admin_id' => $admin->id]);
        $newScenario = Scenario::factory()->create();

        Livewire::test(UserManager::class)
            ->call('edit', $admin->id)
            ->set('scenario_ids', [$newScenario->id])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNull($oldScenario->fresh()->admin_id);
        $this->assertSame($admin->id, $newScenario->fresh()->admin_id);
    }

    public function test_it_deletes_a_user(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $target = User::factory()->create();

        Livewire::test(UserManager::class)
            ->call('delete', $target->id)
            ->assertSet('successMessage', 'Usuario eliminado.');

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_a_coordinador_cannot_delete_their_own_account(): void
    {
        $coordinador = User::factory()->create(['role' => 'coordinador']);
        $this->actingAs($coordinador);

        Livewire::test(UserManager::class)
            ->call('delete', $coordinador->id)
            ->assertHasErrors('delete');

        $this->assertDatabaseHas('users', ['id' => $coordinador->id]);
    }

    public function test_email_must_be_unique_but_ignores_the_user_being_edited(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $existing = User::factory()->create(['email' => 'taken@ucol.mx']);
        $editing = User::factory()->create(['email' => 'editing@ucol.mx']);

        Livewire::test(UserManager::class)
            ->call('edit', $editing->id)
            ->set('email', 'editing@ucol.mx')
            ->call('save')
            ->assertHasNoErrors();

        Livewire::test(UserManager::class)
            ->call('edit', $editing->id)
            ->set('email', 'taken@ucol.mx')
            ->call('save')
            ->assertHasErrors('email');
    }
}
