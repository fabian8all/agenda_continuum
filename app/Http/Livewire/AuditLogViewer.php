<?php

namespace App\Http\Livewire;

use App\Models\AuditLog;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class AuditLogViewer extends Component
{
    #[Url]
    public string $action = '';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public const ACTIONS = ['created', 'approved', 'rejected', 'completed', 'canceled'];

    protected function baseQuery()
    {
        return AuditLog::query()
            ->with(['event.scenario', 'user'])
            ->when($this->action, fn ($q) => $q->where('action', $this->action))
            ->when($this->from, fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('created_at', '<=', $this->to));
    }

    #[Computed]
    public function logs()
    {
        return $this->baseQuery()->latest()->limit(200)->get();
    }

    public function exportUrl()
    {
        return route('auditoria.exportar', [
            'action' => $this->action,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }

    public function render()
    {
        return view('livewire.audit-log-viewer');
    }
}
