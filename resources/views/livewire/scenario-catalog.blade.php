<div>
    <h1 class="h4 fw-semibold mb-4">Catálogo de Escenarios</h1>
    <ul class="list-group">
        @foreach($scenarios as $scenario)
            <li class="list-group-item">
                <strong>{{ $scenario->name }}</strong> – Capacidad: {{ $scenario->capacity }}
                @if($scenario->admin)
                    <br>Administrador: {{ $scenario->admin->name }}
                @endif
                @if($scenario->resources)
                    <br>Recursos: {{ implode(', ', array_keys(array_filter($scenario->resources))) }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
