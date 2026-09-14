<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scenario;

class ScenarioCatalog extends Component
{
    public $scenarios;

    public function mount()
    {
        $this->scenarios = Scenario::with('admin')->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.scenario-catalog');
    }
}
?>
