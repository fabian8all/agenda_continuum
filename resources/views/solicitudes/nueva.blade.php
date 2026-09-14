<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nueva solicitud de uso</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased bg-gray-50">
        @livewire('reservation-form')
        @livewireScripts
    </body>
</html>
