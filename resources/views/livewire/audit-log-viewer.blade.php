<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <h1 class="h4 fw-semibold mb-0">Auditoría y Registro</h1>
        <a href="{{ $this->exportUrl() }}" class="btn btn-outline-secondary btn-sm">Exportar CSV</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <label for="action" class="form-label">Acción</label>
            <select wire:model.live="action" id="action" class="form-select">
                <option value="">Todas</option>
                @foreach (\App\Http\Livewire\AuditLogViewer::ACTIONS as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-4">
            <label for="from" class="form-label">Desde</label>
            <input type="date" wire:model.live="from" id="from" class="form-control">
        </div>
        <div class="col-12 col-sm-4">
            <label for="to" class="form-label">Hasta</label>
            <input type="date" wire:model.live="to" id="to" class="form-control">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Acción</th>
                    <th scope="col">Escenario</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Descripción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->event?->scenario?->name ?? '—' }}</td>
                        <td>{{ $log->user?->name ?? '—' }}</td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-secondary">No hay registros para este filtro.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
