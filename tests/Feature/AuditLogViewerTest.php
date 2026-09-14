<?php

namespace Tests\Feature;

use App\Http\Livewire\AuditLogViewer;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class AuditLogViewerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_docente_cannot_access_the_audit_log_route(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'docente']))
            ->get('/administracion/auditoria')
            ->assertForbidden();
    }

    public function test_an_admin_cannot_access_the_audit_log_route(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get('/administracion/auditoria')
            ->assertForbidden();
    }

    public function test_a_coordinador_can_access_the_audit_log_route(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']))
            ->get('/administracion/auditoria')
            ->assertOk();
    }

    public function test_it_filters_by_action(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $event = Event::factory()->for(Scenario::factory())->create(['status' => 'pending']);
        $event->update(['status' => 'approved']);

        Livewire::test(AuditLogViewer::class)
            ->set('action', 'approved')
            ->assertSet('logs', fn ($logs) => $logs->count() === 1 && $logs->first()->action === 'approved');
    }

    public function test_it_filters_by_date_range(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $event = Event::factory()->for(Scenario::factory())->create();
        AuditLog::where('event_id', $event->id)->update(['created_at' => Carbon::parse('2026-01-01 10:00:00')]);

        Livewire::test(AuditLogViewer::class)
            ->set('from', '2026-02-01')
            ->assertSet('logs', fn ($logs) => $logs->isEmpty());

        Livewire::test(AuditLogViewer::class)
            ->set('from', '2025-12-01')
            ->set('to', '2026-01-31')
            ->assertSet('logs', fn ($logs) => $logs->count() === 1);
    }

    public function test_exporting_csv_is_forbidden_for_non_coordinador(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get('/administracion/auditoria/exportar')
            ->assertForbidden();
    }

    public function test_exporting_csv_returns_the_expected_rows(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'coordinador']));

        $event = Event::factory()->for(Scenario::factory()->create(['name' => 'Aula CSV']))->create();

        $response = $this->get('/administracion/auditoria/exportar');
        $content = $response->streamedContent();

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('Aula CSV', $content);
        $this->assertStringContainsString('created', $content);
    }
}
