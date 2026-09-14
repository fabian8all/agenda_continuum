<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthSamlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('saml.simulator.enabled', true);
        Config::set('saml.simulator.email', 'docente@ucol.mx');
        Config::set('saml.simulator.name', 'Rodriguez Ortiz Miguel Angel');
        Config::set('saml.simulator.firstname', 'Miguel Angel');
        Config::set('saml.simulator.lastname', 'Rodríguez Ortiz');
    }

    public function test_a_guest_is_redirected_and_then_logged_in_via_the_simulator(): void
    {
        $first = $this->get('/solicitudes/nueva');
        $first->assertRedirect('/solicitudes/nueva');

        $second = $this->get('/solicitudes/nueva');
        $second->assertOk();

        $this->assertAuthenticated();
        $this->assertSame('docente@ucol.mx', auth()->user()->email);
    }

    public function test_it_creates_a_new_user_from_the_simulated_attributes(): void
    {
        $this->assertDatabaseMissing('users', ['email' => 'docente@ucol.mx']);

        $this->get('/solicitudes/nueva');
        $this->get('/solicitudes/nueva');

        $this->assertDatabaseHas('users', [
            'email' => 'docente@ucol.mx',
            'name' => 'Rodriguez Ortiz Miguel Angel',
            'role' => 'docente',
        ]);
    }

    public function test_it_logs_in_the_existing_user_instead_of_duplicating_it(): void
    {
        $existing = User::factory()->create(['email' => 'docente@ucol.mx']);

        $this->get('/solicitudes/nueva');
        $this->get('/solicitudes/nueva');

        $this->assertAuthenticatedAs($existing);
        $this->assertSame(1, User::where('email', 'docente@ucol.mx')->count());
    }

    public function test_public_routes_do_not_require_authentication(): void
    {
        $this->get('/')->assertOk();
        $this->get('/escenarios')->assertOk();
        $this->get('/calendario')->assertOk();

        $this->assertGuest();
    }

    public function test_logout_clears_the_session_and_returns_to_a_guest_state(): void
    {
        $this->get('/solicitudes/nueva');
        $this->get('/solicitudes/nueva');
        $this->assertAuthenticated();

        $this->get('/logout')->assertRedirect('/');

        $this->assertGuest();
    }
}
