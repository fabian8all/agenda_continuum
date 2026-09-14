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
        <a href="#main-content" class="visually-hidden-focusable">Saltar al contenido principal</a>

        <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom" aria-label="Principal">
            <div class="container">
                <a href="{{ route('home') }}" class="navbar-brand fw-semibold">Agenda de Escenarios</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Abrir menú de navegación">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="main-nav">
                    @php
                        $navLink = fn (string $route) => request()->routeIs($route) ? ' active' : '';
                    @endphp

                    <ul class="navbar-nav gap-md-3 mb-2 mb-md-0">
                        <li class="nav-item">
                            <a href="{{ route('escenarios.index') }}" class="nav-link{{ $navLink('escenarios.index') }}" @if (request()->routeIs('escenarios.index')) aria-current="page" @endif>Escenarios</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('calendario.index') }}" class="nav-link{{ $navLink('calendario.index') }}" @if (request()->routeIs('calendario.index')) aria-current="page" @endif>Calendario</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('solicitudes.crear') }}" class="nav-link{{ $navLink('solicitudes.crear') }}" @if (request()->routeIs('solicitudes.crear')) aria-current="page" @endif>Nueva solicitud</a>
                        </li>
                        @auth
                            @if (in_array(auth()->user()->role, ['admin', 'coordinador'], true))
                                <li class="nav-item">
                                    <a href="{{ route('solicitudes.gestion') }}" class="nav-link{{ $navLink('solicitudes.gestion') }}" @if (request()->routeIs('solicitudes.gestion')) aria-current="page" @endif>Gestión de solicitudes</a>
                                </li>
                            @endif
                            @if (auth()->user()->role === 'coordinador')
                                <li class="nav-item">
                                    <a href="{{ route('administracion.usuarios') }}" class="nav-link{{ $navLink('administracion.usuarios') }}" @if (request()->routeIs('administracion.usuarios')) aria-current="page" @endif>Administración</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('administracion.auditoria') }}" class="nav-link{{ $navLink('administracion.auditoria') }}" @if (request()->routeIs('administracion.auditoria')) aria-current="page" @endif>Auditoría</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <div class="ms-md-auto small py-2 py-md-0">
                        @auth
                            <span class="text-secondary">{{ auth()->user()->name }}</span>
                            <a href="{{ route('logout') }}" class="ms-3 text-secondary">Cerrar sesión</a>
                        @else
                            <a href="{{ route('login') }}" class="text-secondary">Iniciar sesión</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main id="main-content" class="container py-4">
            @yield('content')
        </main>

        @livewireScripts
    </body>
</html>
