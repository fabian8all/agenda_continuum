<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Event;

class EventObserver
{
    protected const STATUS_TO_ACTION = [
        'approved' => 'approved',
        'rejected' => 'rejected',
        'completed' => 'completed',
        'canceled' => 'canceled',
    ];

    public function created(Event $event): void
    {
        $this->log($event, 'created', "Solicitud creada para {$event->scenario->name}.");
    }

    public function updated(Event $event): void
    {
        if (! $event->wasChanged('status')) {
            return;
        }

        $action = self::STATUS_TO_ACTION[$event->status] ?? null;

        if (! $action) {
            return;
        }

        $this->log($event, $action, "Solicitud de {$event->scenario->name} cambió a estado \"{$event->status}\".");
    }

    protected function log(Event $event, string $action, string $description): void
    {
        AuditLog::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
