<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Agenda de Escenarios')</title>
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom">
            <div class="container">
                <a href="{{ route('home') }}" class="navbar-brand fw-semibold">Agenda de Escenarios</a>

                <div class="d-flex flex-wrap align-items-center gap-3 gap-md-4 ms-md-4">
                    <a href="{{ route('escenarios.index') }}" class="nav-link p-0 text-secondary">Escenarios</a>
                    <a href="{{ route('calendario.index') }}" class="nav-link p-0 text-secondary">Calendario</a>
                    <a href="{{ route('solicitudes.crear') }}" class="nav-link p-0 text-secondary">Nueva solicitud</a>
                    @auth
                        @if (in_array(auth()->user()->role, ['admin', 'coordinador'], true))
                            <a href="{{ route('solicitudes.gestion') }}" class="nav-link p-0 text-secondary">Gestión de solicitudes</a>
                        @endif
                        @if (auth()->user()->role === 'coordinador')
                            <a href="{{ route('administracion.usuarios') }}" class="nav-link p-0 text-secondary">Administración</a>
                            <a href="{{ route('administracion.auditoria') }}" class="nav-link p-0 text-secondary">Auditoría</a>
                        @endif
                    @endauth
                </div>

                <div class="ms-md-auto small">
                    @auth
                        <span class="text-secondary">{{ auth()->user()->name }}</span>
                        <a href="{{ route('logout') }}" class="ms-3 text-secondary">Cerrar sesión</a>
                    @else
                        <a href="{{ route('login') }}" class="text-secondary">Iniciar sesión</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="container py-4">
            @yield('content')
        </main>

        @livewireScripts
    </body>
</html>
