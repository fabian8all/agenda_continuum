<div>
    <h1 class="h4 fw-semibold mb-4">Calendario de Eventos</h1>
    <ul class="list-group">
        @foreach($events as $event)
            <li class="list-group-item">
                <strong>{{ $event->title ?? 'Evento' }}</strong>
                <br>Escenario: {{ $event->scenario->name ?? 'N/A' }}
                <br>Desde: {{ $event->start_time }}
                <br>Hasta: {{ $event->end_time }}
                <br>Estado: {{ $event->status }}
            </li>
        @endforeach
    </ul>
</div>
