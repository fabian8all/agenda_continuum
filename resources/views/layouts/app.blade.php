<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Agenda de Escenarios')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased bg-gray-50 text-gray-900">
        <nav class="bg-white border-b">
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center gap-6">
                <a href="{{ route('home') }}" class="font-semibold">Agenda de Escenarios</a>
                <a href="{{ route('escenarios.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Escenarios</a>
                <a href="{{ route('calendario.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Calendario</a>
                <a href="{{ route('solicitudes.crear') }}" class="text-sm text-gray-600 hover:text-gray-900">Nueva solicitud</a>
            </div>
        </nav>

        <main class="max-w-5xl mx-auto px-6 py-8">
            @yield('content')
        </main>

        @livewireScripts
    </body>
</html>
