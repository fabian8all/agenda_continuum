<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Livewire\Component;

class RequestManager extends Component
{
    public $pendingEvents;
    public $closableEvents;

    public $successMessage = '';
    public $errorMessage = '';

    public function mount()
    {
        $this->refreshLists();
    }

    protected function scopeToOwnScenarios($query)
    {
        if (auth()->user()->role === 'admin') {
            $query->whereHas('scenario', fn ($q) => $q->where('admin_id', auth()->id()));
        }

        return $query;
    }

    protected function refreshLists()
    {
        $this->pendingEvents = $this->scopeToOwnScenarios(
            Event::with('scenario')->where('status', 'pending')
        )->orderBy('start_time')->get();

        $this->closableEvents = $this->scopeToOwnScenarios(
            Event::with('scenario')->where('status', 'approved')->where('end_time', '<', Carbon::now())
        )->orderBy('start_time')->get();
    }

    public function approve(int $eventId)
    {
        $this->transition($eventId, from: 'pending', to: 'approved', successMessage: 'Solicitud aprobada.');
    }

    public function reject(int $eventId)
    {
        $this->transition($eventId, from: 'pending', to: 'rejected', successMessage: 'Solicitud rechazada.');
    }

    public function complete(int $eventId)
    {
        $this->transition(
            $eventId,
            from: 'approved',
            to: 'completed',
            successMessage: 'Evento marcado como terminado.',
            requirePastEnd: true,
        );
    }

    public function cancel(int $eventId)
    {
        $this->transition(
            $eventId,
            from: 'approved',
            to: 'canceled',
            successMessage: 'Evento cancelado.',
            requirePastEnd: true,
        );
    }

    protected function transition(int $eventId, string $from, string $to, string $successMessage, bool $requirePastEnd = false)
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        $event = $this->scopeToOwnScenarios(Event::with('scenario')->where('id', $eventId))->first();

        if (! $event || $event->status !== $from || ($requirePastEnd && $event->end_time->isFuture())) {
            $this->errorMessage = 'Esa solicitud ya no está disponible para esta acción.';
            $this->refreshLists();

            return;
        }

        $event->update(['status' => $to]);
        $this->successMessage = $successMessage;
        $this->refreshLists();
    }

    public function render()
    {
        return view('livewire.request-manager');
    }
}
