<div>
    <h1 class="h4 fw-semibold mb-4">Calendario de Eventos</h1>
    <div class="list-group">
        @foreach($events as $event)
            <div class="list-group-item">
                <strong>{{ $event->title ?? 'Evento' }}</strong>
                <br>Escenario: {{ $event->scenario->name ?? 'N/A' }}
                <br>Desde: {{ $event->start_time }}
                <br>Hasta: {{ $event->end_time }}
                <br>Estado: {{ $event->status }}
            </div>
        @endforeach
    </div>
</div>
