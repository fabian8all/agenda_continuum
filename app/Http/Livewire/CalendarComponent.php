<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scenario;

class CalendarComponent extends Component
{
    public $events;

    public function mount()
    {
        // Load events for all scenarios; you can add filters later
        $this->events = \App\Models\Event::with('scenario')->orderBy('start_time')->get();
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
?>
