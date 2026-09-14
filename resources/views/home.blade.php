@extends('layouts.app')

@section('title', 'Inicio · Agenda de Escenarios')

@section('content')
    <div class="py-3 py-md-4 mb-3">
        <h1 class="h2 fw-bold mb-2">Agenda de Escenarios Educativos</h1>
        <p class="text-secondary fs-5 mb-0" style="max-width: 42rem;">
            Consulta la disponibilidad de aulas, laboratorios, talleres y auditorios,
            y solicita su uso.
        </p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-4">
            <a href="{{ route('escenarios.index') }}" class="card home-card text-decoration-none h-100">
                <div class="card-body">
                    <h2 class="h6 fw-semibold mb-1">Catálogo de Escenarios</h2>
                    <p class="small text-secondary mb-0">Nombre, capacidad, recursos y administrador de cada espacio.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-4">
            <a href="{{ route('calendario.index') }}" class="card home-card text-decoration-none h-100">
                <div class="card-body">
                    <h2 class="h6 fw-semibold mb-1">Calendario de Eventos</h2>
                    <p class="small text-secondary mb-0">Eventos próximos y su estado (pendiente, aprobado, etc.).</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-4">
            <a href="{{ route('solicitudes.crear') }}" class="card home-card text-decoration-none h-100">
                <div class="card-body">
                    <h2 class="h6 fw-semibold mb-1">Solicitud de Uso</h2>
                    <p class="small text-secondary mb-0">Solicita un escenario validando que no haya solapamientos.</p>
                </div>
            </a>
        </div>
    </div>
@endsection
