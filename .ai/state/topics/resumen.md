# Resumen y stack

Sistema web institucional para la reserva y gestión de escenarios educativos (aulas, laboratorios, talleres y auditorios). Permite a los profesores consultar disponibilidad horaria en tiempo real y solicitar reservas de aulas según capacidad y equipamiento requerido, previniendo el solapamiento de horarios.

**Estado actual:** Modelo de datos (`Scenario`, `Event`) y la primera épica
del MVP completos: Catálogo de Escenarios, Calendario de Eventos y
Solicitud de Uso (formulario con validación de disponibilidad, sin
solapamientos) — ver `.ai/tasks/_closed/SolicitudUso/`. Hay una página de
inicio (`/`) con navegación mínima entre las tres vistas. Pendiente: Gestión
de Solicitudes (aprobación/rechazo).

## Stack
    - **Backend:** Laravel 11.x (PHP 8.2+)
    - **Reactividad y Frontend:** Laravel Livewire v3 + Alpine.js + Tailwind CSS
    - **Base de datos:** MySQL 8.x (Eloquent ORM)
    - **Tests:** Pest PHP / PHPUnit

## Requerimientos clave
- **Catálogo de Escenarios**: listado con nombre, capacidad, recursos y administrador.
- **Calendario de Eventos**: vista filtrable, visualización de estados.
- **Solicitud de Uso**: formulario con validación de disponibilidad.
- **Gestión de Solicitudes**: aprobación, rechazo, cierre, notificaciones.
- **Administración de Usuarios y Roles** (integración SimpleSAML).
- **Acceso Público**: solo lectura del catálogo y calendario.
- **Auditoría**: registro de acciones críticas.
- **No funcionales**: responsividad, accesibilidad WCAG 2.1 AA, seguridad, rendimiento <2 s, escalabilidad (≥500 escenarios, 10 000 solicitudes simultáneas).

## Prioridad (MVP → Release)
1. Escenarios, Calendario y Solicitud (fundamental).
2. Gestión de Solicitudes.
3. Autenticación Federada.
4. Administración de Usuarios.
5. Acceso Público.
6. Auditoría.
7. Responsividad y Accesibilidad (iterativo).

## Próximos pasos
- Implementar Gestión de Solicitudes (aprobar/rechazar, notificaciones,
  cierre/cancelación).
- Establecer sprints de 2 semanas y estimar effort.
- Configurar CI (las pruebas automatizadas con Pest/PHPUnit ya existen).

## Comandos frecuentes

    - Servidor de desarrollo: `php artisan serve` y `npm run dev`
    - Migraciones y seeders: `php artisan migrate:fresh --seed`
    - Pruebas automatizadas: `php artisan test` (o `./vendor/bin/pest`)
    - Diagnóstico Continuum: `tools/continuum doctor`
