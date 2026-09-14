@extends('layouts.app')

@section('title', 'Inicio · Agenda de Escenarios')

@section('content')
    <h1 class="text-2xl font-semibold mb-2">Agenda de Escenarios Educativos</h1>
    <p class="text-gray-600 mb-8">
        Consulta la disponibilidad de aulas, laboratorios, talleres y auditorios,
        y solicita su uso.
    </p>

    <div class="grid gap-4 sm:grid-cols-3">
        <a href="{{ route('escenarios.index') }}" class="block rounded-lg border bg-white p-5 hover:shadow-sm">
            <h2 class="font-medium mb-1">Catálogo de Escenarios</h2>
            <p class="text-sm text-gray-600">Nombre, capacidad, recursos y administrador de cada espacio.</p>
        </a>

        <a href="{{ route('calendario.index') }}" class="block rounded-lg border bg-white p-5 hover:shadow-sm">
            <h2 class="font-medium mb-1">Calendario de Eventos</h2>
            <p class="text-sm text-gray-600">Eventos próximos y su estado (pendiente, aprobado, etc.).</p>
        </a>

        <a href="{{ route('solicitudes.crear') }}" class="block rounded-lg border bg-white p-5 hover:shadow-sm">
            <h2 class="font-medium mb-1">Solicitud de Uso</h2>
            <p class="text-sm text-gray-600">Solicita un escenario validando que no haya solapamientos.</p>
        </a>
    </div>
@endsection
