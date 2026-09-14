<?php

namespace App\Http\Livewire;

use App\Models\Scenario;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class UserManager extends Component
{
    public $users;
    public $scenarios;

    public $editingUserId = null;
    public $name = '';
    public $email = '';
    public $role = 'docente';
    public $scenario_ids = [];

    public $successMessage = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.($this->editingUserId ?: 'NULL').',id',
            'role' => 'required|in:docente,admin,coordinador',
            'scenario_ids' => 'array',
            'scenario_ids.*' => 'exists:scenarios,id',
        ];
    }

    public function mount()
    {
        $this->scenarios = Scenario::orderBy('name')->get();
        $this->refreshUsers();
    }

    protected function refreshUsers()
    {
        $this->users = User::with('administeredScenarios')->orderBy('name')->get();
    }

    public function edit(int $userId)
    {
        $user = User::with('administeredScenarios')->findOrFail($userId);

        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->scenario_ids = $user->administeredScenarios->pluck('id')->all();
        $this->successMessage = '';
    }

    public function resetForm()
    {
        $this->reset(['editingUserId', 'name', 'email', 'role', 'scenario_ids']);
        $this->role = 'docente';
        $this->resetErrorBag();
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ]);
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Str::password(32),
            ]);
        }

        $scenarioIds = $validated['role'] === 'admin' ? ($validated['scenario_ids'] ?? []) : [];

        Scenario::whereIn('id', $scenarioIds)->update(['admin_id' => $user->id]);
        Scenario::where('admin_id', $user->id)->whereNotIn('id', $scenarioIds)->update(['admin_id' => null]);

        $this->successMessage = $this->editingUserId
            ? 'Usuario actualizado.'
            : 'Usuario creado.';

        $this->resetForm();
        $this->scenarios = Scenario::orderBy('name')->get();
        $this->refreshUsers();
    }

    public function delete(int $userId)
    {
        if ($userId === auth()->id()) {
            $this->successMessage = '';
            $this->addError('delete', 'No puedes eliminar tu propia cuenta.');

            return;
        }

        User::findOrFail($userId)->delete();

        if ($this->editingUserId === $userId) {
            $this->resetForm();
        }

        $this->successMessage = 'Usuario eliminado.';
        $this->refreshUsers();
    }

    public function render()
    {
        return view('livewire.user-manager');
    }
}
