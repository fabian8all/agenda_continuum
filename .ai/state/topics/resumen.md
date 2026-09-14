# Resumen y stack

Sistema web institucional para la reserva y gestión de escenarios educativos (aulas, laboratorios, talleres y auditorios). Permite a los profesores consultar disponibilidad horaria en tiempo real y solicitar reservas de aulas según capacidad y equipamiento requerido, previniendo el solapamiento de horarios.

**Estado actual:** Modelo de datos (`Scenario`, `Event`) y las dos primeras
épicas del MVP completas: Catálogo de Escenarios, Calendario de Eventos,
Solicitud de Uso (con validación de disponibilidad) y Gestión de
Solicitudes (aprobar/rechazar, marcar terminado/cancelar) — ver
`.ai/tasks/_closed/SolicitudUso/` y `.ai/tasks/_closed/GestionSolicitudes/`.
Hay una página de inicio (`/`) con navegación entre las cuatro vistas.
`events.requester_id` ya vincula la solicitud con el usuario que la creó
(nullable, ver `.ai/tasks/_closed/SolicitanteEvent/`). Autenticación
Federada (SimpleSAML) implementada con simulador vía `.env`, replicando el
patrón de `../redi/redi-app` (ver `.ai/tasks/_closed/AutenticacionFederada/`);
`/solicitudes/nueva` y `/solicitudes` ya requieren login, catálogo/calendario/
inicio siguen públicos. El flujo SAML real contra el IdP de DGRE queda
wireado pero sin validar (el `entityId` de esta app aún no está registrado
con el IdP). Todavía no hay control de acceso por rol/escenario: cualquier
usuario logueado puede gestionar cualquier solicitud. El frontend se migró
de Tailwind a Bootstrap 5 + Sass (ver `.ai/tasks/_closed/MigrarBootstrap/`);
el ambiente de desarrollo local usa `docker-compose.yml` +
`docker-compose.override.yml` (ver `.ai/tasks/_closed/DockerComposeSplit/`).

## Stack
    - **Backend:** Laravel 11.x (PHP 8.2+)
    - **Reactividad y Frontend:** Laravel Livewire v3 + Alpine.js + Bootstrap 5 (Sass) — no se usa Tailwind (ver `docs/requirements.md`)
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
- Administración de Usuarios y Roles (prioridad 4): control de acceso por
  rol/escenario en `RequestManager`, hoy inexistente.
- Registrar el `entityId` de esta app con el administrador del IdP de DGRE
  para poder probar el flujo SAML real (`SAML_SIMULATOR=false`).
- Notificaciones por correo al solicitante (ya desbloqueadas por
  `events.requester_id`, pendientes de implementar).
- Establecer sprints de 2 semanas y estimar effort.
- Configurar CI (las pruebas automatizadas con Pest/PHPUnit ya existen).

## Comandos frecuentes

    - Servidor de desarrollo: `php artisan serve` y `npm run dev`
    - Migraciones y seeders: `php artisan migrate:fresh --seed`
    - Pruebas automatizadas: `php artisan test` (o `./vendor/bin/pest`)
    - Diagnóstico Continuum: `tools/continuum doctor`
