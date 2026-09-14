<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Event;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_event_logs_a_created_action(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::factory()->for(Scenario::factory())->create();

        $this->assertDatabaseHas('audit_logs', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'action' => 'created',
        ]);
    }

    #[DataProvider('statusTransitions')]
    public function test_changing_status_logs_the_matching_action(string $from, string $to): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::factory()->for(Scenario::factory())->create(['status' => $from]);
        $event->update(['status' => $to]);

        $this->assertDatabaseHas('audit_logs', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'action' => $to,
        ]);
    }

    public static function statusTransitions(): array
    {
        return [
            'approve' => ['pending', 'approved'],
            'reject' => ['pending', 'rejected'],
            'complete' => ['approved', 'completed'],
            'cancel' => ['approved', 'canceled'],
        ];
    }

    public function test_updating_a_field_other_than_status_does_not_log_an_extra_action(): void
    {
        $this->actingAs(User::factory()->create());

        $event = Event::factory()->for(Scenario::factory())->create();
        $event->update(['description' => 'Nueva descripción']);

        $this->assertSame(1, AuditLog::where('event_id', $event->id)->count());
    }

    public function test_deleting_an_event_keeps_its_audit_logs(): void
    {
        $this->actingAs(User::factory()->create());

        $event = Event::factory()->for(Scenario::factory())->create();
        $logId = AuditLog::where('event_id', $event->id)->firstOrFail()->id;

        $event->delete();

        $this->assertDatabaseHas('audit_logs', ['id' => $logId, 'event_id' => null]);
    }
}
