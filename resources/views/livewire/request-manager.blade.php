<div>
    <h1 class="h4 fw-semibold mb-4">Gestión de Solicitudes</h1>

    @if ($successMessage)
        <div class="alert alert-success">{{ $successMessage }}</div>
    @endif
    @if ($errorMessage)
        <div class="alert alert-danger">{{ $errorMessage }}</div>
    @endif

    <section class="mb-4">
        <h2 class="h6 fw-medium mb-3">Solicitudes pendientes</h2>

        @forelse ($pendingEvents as $event)
            <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-2 bg-white">
                <div>
                    <p class="fw-medium mb-0">{{ $event->title ?? 'Solicitud' }} — {{ $event->scenario->name }}</p>
                    <p class="small text-secondary mb-0">{{ $event->start_time->format('d/m/Y H:i') }} a {{ $event->end_time->format('H:i') }}</p>
                    @if ($event->description)
                        <p class="small text-secondary mb-0">{{ $event->description }}</p>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button type="button" wire:click="approve({{ $event->id }})" class="btn btn-success btn-sm">Aprobar</button>
                    <button type="button" wire:click="reject({{ $event->id }})" class="btn btn-danger btn-sm">Rechazar</button>
                </div>
            </div>
        @empty
            <p class="small text-secondary">No hay solicitudes pendientes.</p>
        @endforelse
    </section>

    <section>
        <h2 class="h6 fw-medium mb-3">Eventos por cerrar</h2>

        @forelse ($closableEvents as $event)
            <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-2 bg-white">
                <div>
                    <p class="fw-medium mb-0">{{ $event->title ?? 'Evento' }} — {{ $event->scenario->name }}</p>
                    <p class="small text-secondary mb-0">{{ $event->start_time->format('d/m/Y H:i') }} a {{ $event->end_time->format('H:i') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" wire:click="complete({{ $event->id }})" class="btn btn-primary btn-sm">Marcar terminado</button>
                    <button type="button" wire:click="cancel({{ $event->id }})" class="btn btn-secondary btn-sm">Cancelar</button>
                </div>
            </div>
        @empty
            <p class="small text-secondary">No hay eventos aprobados pendientes de cerrar.</p>
        @endforelse
    </section>
</div>
