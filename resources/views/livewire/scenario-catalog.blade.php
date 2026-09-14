<div>
    <h1>Catálogo de Escenarios</h1>
    <ul>
        @foreach($scenarios as $scenario)
            <li>
                <strong>{{ $scenario->name }}</strong> – Capacidad: {{ $scenario->capacity }}
                @if($scenario->admin)
                    <br/>Administrador: {{ $scenario->admin->name }}
                @endif
                @if($scenario->resources)
                    <br/>Recursos: {{ implode(', ', array_keys(array_filter($scenario->resources))) }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
