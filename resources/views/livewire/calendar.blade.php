<div>
    <h1>Calendario de Eventos</h1>
    <ul>
        @foreach($events as $event)
            <li>
                <strong>{{ $event->title ?? 'Evento' }}</strong>
                <br/>Escenario: {{ $event->scenario->name ?? 'N/A' }}
                <br/>Desde: {{ $event->start_time }}
                <br/>Hasta: {{ $event->end_time }}
                <br/>Estado: {{ $event->status }}
            </li>
        @endforeach
    </ul>
</div>
