# Resumen y stack

Sistema web institucional para la reserva y gestión de escenarios educativos (aulas, laboratorios, talleres y auditorios). Permite a los profesores consultar disponibilidad horaria en tiempo real y solicitar reservas de aulas según capacidad y equipamiento requerido, previniendo el solapamiento de horarios.

**Estado actual:** Las 6 épicas fundamentales del MVP están completas:
Catálogo de Escenarios, Calendario de Eventos, Solicitud de Uso (con
validación de disponibilidad), Gestión de Solicitudes (aprobar/rechazar,
marcar terminado/cancelar), Autenticación Federada (SimpleSAML con
simulador), Administración de Usuarios y Roles, y Auditoría y Registro —
ver `.ai/tasks/_closed/`. `events.requester_id` vincula cada solicitud
con su autor. Cada creación/aprobación/rechazo/cierre de un `Event` se
registra automáticamente en `audit_logs` (vía `EventObserver`), consultable
y exportable a CSV en `/administracion/auditoria`. Control de acceso por
rol ya implementado: `docente` (Solicitante)
solo crea solicitudes; `admin` (Administrador de Escenario) solo
gestiona/cierra eventos de sus escenarios asignados
(`scenarios.admin_id`); `coordinador` (Administrador General) tiene
acceso total, incluida la administración de usuarios en
`/administracion/usuarios`. Catálogo, calendario e inicio siguen
públicos. El flujo SAML real contra el IdP de DGRE queda wireado pero sin
validar (el `entityId` de esta app aún no está registrado con el IdP); el
primer `coordinador` se crea con `php artisan users:set-role {email}
coordinador`. El frontend usa Bootstrap 5 + Sass (no Tailwind, ver
`docs/requirements.md`); el ambiente local usa `docker-compose.yml` +
`docker-compose.override.yml`.

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
- Revisar si falta algo explícito de "Acceso Público" (prioridad 5) — en
  la práctica ya está satisfecho (catálogo/calendario/inicio públicos).
- Responsividad y Accesibilidad WCAG 2.1 AA (prioridad 7, iterativa).
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
    - Promover al primer administrador general: `php artisan users:set-role <email> coordinador`
