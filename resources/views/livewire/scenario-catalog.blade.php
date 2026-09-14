<div>
    <h1 class="h4 fw-semibold mb-4">Catálogo de Escenarios</h1>
    <ul class="row g-3 list-unstyled">
        @foreach($scenarios as $scenario)
            <li class="col-12 col-sm-6 col-lg-4">
                <div class="card home-card h-100">
                    <div class="card-body">
                        <h2 class="h6 fw-semibold mb-1">{{ $scenario->name }}</h2>
                        <p class="small text-secondary mb-2">Capacidad: {{ $scenario->capacity }}</p>

                        @if($scenario->admin)
                            <p class="small text-secondary mb-2">Administrador: {{ $scenario->admin->name }}</p>
                        @endif

                        @if($scenario->resources)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(array_keys(array_filter($scenario->resources)) as $resource)
                                    <span class="badge text-bg-light border">{{ $resource }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
