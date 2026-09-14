<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\Scenario;
use Livewire\Component;

class ReservationForm extends Component
{
    public $scenarios = [];

    public $scenario_id = '';
    public $start_time = '';
    public $end_time = '';
    public $description = '';

    public $successMessage = '';

    protected function rules()
    {
        return [
            'scenario_id' => 'required|exists:scenarios,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function mount()
    {
        $this->scenarios = Scenario::orderBy('name')->get();
    }

    public function submit()
    {
        $this->successMessage = '';

        $validated = $this->validate();

        $overlaps = Event::query()
            ->where('scenario_id', $validated['scenario_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->exists();

        if ($overlaps) {
            $this->addError('start_time', 'El escenario ya tiene una solicitud en ese horario. Elige otro horario.');

            return;
        }

        Event::create([
            'scenario_id' => $validated['scenario_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        $this->reset(['scenario_id', 'start_time', 'end_time', 'description']);
        $this->successMessage = 'Tu solicitud fue registrada y quedó pendiente de aprobación.';
    }

    public function render()
    {
        return view('livewire.reservation-form');
    }
}
