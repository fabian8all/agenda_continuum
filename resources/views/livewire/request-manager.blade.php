<div class="space-y-8">
    <h1 class="text-xl font-semibold">Gestión de Solicitudes</h1>

    @if ($successMessage)
        <div class="rounded-md bg-green-50 text-green-800 px-4 py-3">{{ $successMessage }}</div>
    @endif
    @if ($errorMessage)
        <div class="rounded-md bg-red-50 text-red-800 px-4 py-3">{{ $errorMessage }}</div>
    @endif

    <section>
        <h2 class="font-medium mb-3">Solicitudes pendientes</h2>

        @forelse ($pendingEvents as $event)
            <div class="flex items-center justify-between rounded-lg border bg-white p-4 mb-2">
                <div>
                    <p class="font-medium">{{ $event->title ?? 'Solicitud' }} — {{ $event->scenario->name }}</p>
                    <p class="text-sm text-gray-600">{{ $event->start_time->format('d/m/Y H:i') }} a {{ $event->end_time->format('H:i') }}</p>
                    @if ($event->description)
                        <p class="text-sm text-gray-500">{{ $event->description }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="approve({{ $event->id }})" class="rounded-md bg-green-600 px-3 py-1.5 text-sm text-white">Aprobar</button>
                    <button type="button" wire:click="reject({{ $event->id }})" class="rounded-md bg-red-600 px-3 py-1.5 text-sm text-white">Rechazar</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No hay solicitudes pendientes.</p>
        @endforelse
    </section>

    <section>
        <h2 class="font-medium mb-3">Eventos por cerrar</h2>

        @forelse ($closableEvents as $event)
            <div class="flex items-center justify-between rounded-lg border bg-white p-4 mb-2">
                <div>
                    <p class="font-medium">{{ $event->title ?? 'Evento' }} — {{ $event->scenario->name }}</p>
                    <p class="text-sm text-gray-600">{{ $event->start_time->format('d/m/Y H:i') }} a {{ $event->end_time->format('H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="complete({{ $event->id }})" class="rounded-md bg-blue-600 px-3 py-1.5 text-sm text-white">Marcar terminado</button>
                    <button type="button" wire:click="cancel({{ $event->id }})" class="rounded-md bg-gray-600 px-3 py-1.5 text-sm text-white">Cancelar</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No hay eventos aprobados pendientes de cerrar.</p>
        @endforelse
    </section>
</div>
