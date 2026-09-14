# Handoff: AuditoriaRegistro

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Registrar las acciones críticas sobre las solicitudes (creación,
aprobación, rechazo, finalización, cancelación) y dar una vista de
consulta filtrable y exportable a CSV (ver `task.md`).

## Archivos revisados
- `docs/requirements.md` (requerimiento funcional 7),
  `docs/backlog.md` (historia 8)
- `.ai/tasks/_closed/AdministracionUsuarios/handoff.md` (patrón de
  middleware `role:` ya establecido)
- `app/Models/Event.php` (estados válidos)

## Archivos modificados/creados
- `database/migrations/2026_09_14_160846_create_audit_logs_table.php`
  (nuevo) — `event_id`/`user_id` nullable con `onDelete('set null')`,
  `action`, `description`, índices en `action` y `created_at`.
- `app/Models/AuditLog.php` (nuevo).
- `app/Observers/EventObserver.php` (nuevo) — `created()` registra
  `action=created`; `updated()` registra `approved`/`rejected`/
  `completed`/`canceled` solo cuando `status` cambió de verdad
  (`wasChanged('status')`), evitando logs duplicados por otros campos.
- `app/Providers/AppServiceProvider.php` — `Event::observe(EventObserver::class)`
  en `boot()`.
- `app/Http/Livewire/AuditLogViewer.php` + `resources/views/livewire/audit-log-viewer.blade.php`
  (nuevos) — filtros `action`/`from`/`to` con `#[Url]` (persistidos en la
  querystring) y `#[Computed]` para la lista.
- `app/Http/Controllers/AuditLogExportController.php` (nuevo) — CSV vía
  `response()->streamDownload()`, mismos filtros por query string.
- `resources/views/administracion/auditoria.blade.php` (nuevo).
- `routes/web.php` — `/administracion/auditoria` y
  `/administracion/auditoria/exportar`, ambas `role:coordinador`.
- `resources/views/layouts/app.blade.php` — enlace "Auditoría" solo para
  `coordinador`.
- `tests/Feature/AuditLogTest.php` (7 tests),
  `tests/Feature/AuditLogViewerTest.php` (7 tests).

## Decisión(es) tomada(s)
- **Observer en vez de logging manual** en `ReservationForm` y
  `RequestManager`: una sola fuente de verdad para todas las transiciones
  de `Event`, presentes y futuras — cualquier código nuevo que cambie
  `status` queda auditado automáticamente sin tener que acordarse de
  llamar a nada explícitamente.
- **El actor es `auth()->id()` en el momento de la operación**, no
  `event->requester_id`: para `created` ambos coinciden (la ruta de
  creación ya exige `verify.auth`), pero para las transiciones de
  `RequestManager` el actor correcto es quien aprueba/rechaza/cierra, no
  quien originalmente pidió el escenario.
- **Filtros solo por `action` y rango de fechas** (lo que pide
  explícitamente la historia): no se agregó filtro por escenario ni por
  usuario, para no ampliar el alcance sin que se pidiera.
- **Límite de 200 filas** en la vista de consulta (no en la exportación
  CSV, que trae todo lo que cumpla el filtro): la historia no pide
  paginación y el volumen actual no la necesita; se documentó como fuera
  de alcance en `task.md`.
- **Acceso restringido a `coordinador`** únicamente (igual que
  `/administracion/usuarios`): la historia dice explícitamente "Como
  administrador general"; un `admin` (Administrador de Escenario) no ve
  auditoría ni de sus propios escenarios.

## Suposiciones vigentes
- Se asume que solo los cambios de estado de `Event` cuentan como
  "acciones críticas" para esta fase (no alta de usuarios, escenarios,
  ni cambios de rol) — así lo acota el requerimiento funcional 7.

## Validación
- Ejecutada: `sail artisan migrate --force` (1 migración OK) · `sail
  artisan test` (59/59 passed, 129 assertions, incluye los 14 tests
  nuevos sin regresiones) · verificación manual con `curl`: se promovió
  un usuario a `coordinador`, se generaron transiciones reales de estado,
  se confirmó que aparecen en `/administracion/auditoria`, que el filtro
  `?action=approved` (persistido en la URL vía `#[Url]`) reduce la tabla
  correctamente, y que `/administracion/auditoria/exportar` devuelve un
  CSV válido; se confirmó que borrar el usuario actor deja
  `audit_logs.user_id` en `null` sin romper el registro. Datos de prueba
  limpiados al terminar.
- No ejecutada / pendiente: revisión visual humana en navegador real del
  formulario de filtros.

## Riesgos / dudas abiertas
- Ninguno nuevo.

## Siguiente paso recomendado
Con las 6 épicas fundamentales del MVP completas más Auditoría, el
trabajo restante documentado en `resumen.md` es de acabado: Acceso
Público (ya prácticamente satisfecho, conviene solo verificarlo
explícitamente), Responsividad y Accesibilidad WCAG 2.1 AA (iterativo,
prioridad 7), y las tareas de proceso pendientes (sprints, CI). También
sigue abierto registrar el `entityId` de esta app con el IdP real de DGRE
para validar el flujo SAML no simulado.
