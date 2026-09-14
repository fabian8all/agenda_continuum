@extends('layouts.app')

@section('title', 'Inicio · Agenda de Escenarios')

@section('content')
    <h1 class="h3 fw-semibold mb-2">Agenda de Escenarios Educativos</h1>
    <p class="text-secondary mb-4">
        Consulta la disponibilidad de aulas, laboratorios, talleres y auditorios,
        y solicita su uso.
    </p>

    <div class="row g-3">
        <div class="col-12 col-sm-4">
            <a href="{{ route('escenarios.index') }}" class="card text-decoration-none h-100">
                <div class="card-body">
                    <h2 class="h6 fw-medium mb-1">Catálogo de Escenarios</h2>
                    <p class="small text-secondary mb-0">Nombre, capacidad, recursos y administrador de cada espacio.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-4">
            <a href="{{ route('calendario.index') }}" class="card text-decoration-none h-100">
                <div class="card-body">
                    <h2 class="h6 fw-medium mb-1">Calendario de Eventos</h2>
                    <p class="small text-secondary mb-0">Eventos próximos y su estado (pendiente, aprobado, etc.).</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-4">
            <a href="{{ route('solicitudes.crear') }}" class="card text-decoration-none h-100">
                <div class="card-body">
                    <h2 class="h6 fw-medium mb-1">Solicitud de Uso</h2>
                    <p class="small text-secondary mb-0">Solicita un escenario validando que no haya solapamientos.</p>
                </div>
            </a>
        </div>
    </div>
@endsection
