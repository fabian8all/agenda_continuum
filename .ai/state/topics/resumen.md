# Resumen y stack

Sistema web institucional para la reserva y gestión de escenarios educativos (aulas, laboratorios, talleres y auditorios). Permite a los profesores consultar disponibilidad horaria en tiempo  
real y solicitar reservas de aulas según capacidad y equipamiento requerido, previniendo el solapamiento de horarios.

**Estado actual:** Fase inicial. Protocolo Continuum configurado; pendiente inicializar estructura base de Laravel y migraciones.

## Stack
    - **Backend:** Laravel 11.x (PHP 8.2+)
    - **Reactividad y Frontend:** Laravel Livewire v3 + Alpine.js + Tailwind CSS
    - **Base de datos:** MySQL 8.x (Eloquent ORM)
    - **Tests:** Pest PHP / PHPUnit   
## Comandos frecuentes

    - Servidor de desarrollo: `php artisan serve` y `npm run dev`                                                                                                                                   
    - Migraciones y seeders: `php artisan migrate:fresh --seed`                                                                                                                                     
    - Pruebas automatizadas: `php artisan test` (o `./vendor/bin/pest`)                                                                                                                             
    - Diagnóstico Continuum: `tools/continuum doctor`   