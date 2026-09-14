# Tarea: AuditoriaRegistro

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Registrar las acciones críticas sobre las solicitudes (creación,
aprobación, rechazo, finalización, cancelación) y dar una vista de
consulta filtrable y exportable a CSV — requerimiento funcional 7 de
`docs/requirements.md`, historia 8 de `docs/backlog.md`.

## Incluido en el alcance
- Tabla `audit_logs` (`event_id` nullable con `onDelete('set null')`,
  `user_id` nullable con `onDelete('set null')`, `action` string, índice
  por `action` y `created_at`).
- `App\Models\AuditLog`.
- `App\Observers\EventObserver`: escucha `created` (acción `created`) y
  `updated` cuando cambia `status` (`approved`, `rejected`, `completed`,
  `canceled`), registrado en `AppServiceProvider::boot()`. El actor es
  `auth()->id()` en el momento de la operación — ya siempre hay un
  usuario autenticado, porque tanto crear como gestionar solicitudes
  quedaron protegidos por `verify.auth` en tareas anteriores.
- `App\Http\Livewire\AuditLogViewer` (Livewire, filtros por `action` y
  rango de fechas persistidos en la URL con `#[Url]`) en
  `/administracion/auditoria`, protegida con `role:coordinador` (misma
  restricción que `/administracion/usuarios`: la historia dice
  explícitamente "Como administrador general").
- Exportación a CSV: ruta `/administracion/auditoria/exportar` (mismos
  filtros por query string), protegida igual, streaming de CSV.
- Enlace de navegación "Auditoría" visible solo para `coordinador`.

## Explícitamente fuera de alcance
- Auditar acciones fuera del ciclo de vida de `Event` (creación de
  escenarios, alta de usuarios, cambios de rol): la historia y el
  requerimiento funcional solo piden "creación, aprobación, rechazo,
  finalización, cancelación", que son estados de `Event`.
- Paginación de la tabla de consulta más allá de un límite razonable
  (se limita a 200 filas más recientes que cumplan el filtro); no se
  pidió paginación explícita y el volumen de datos de esta fase no lo
  necesita.
- Retención/purga de logs antiguos.

## Write-set (archivos que se espera tocar)
- `database/migrations/2026_09_14_XXXXXX_create_audit_logs_table.php` (nuevo)
- `app/Models/AuditLog.php` (nuevo)
- `app/Observers/EventObserver.php` (nuevo)
- `app/Providers/AppServiceProvider.php` (registra el observer)
- `app/Http/Livewire/AuditLogViewer.php` (nuevo)
- `resources/views/livewire/audit-log-viewer.blade.php` (nuevo)
- `resources/views/administracion/auditoria.blade.php` (nuevo)
- `app/Http/Controllers/AuditLogExportController.php` (nuevo)
- `routes/web.php`
- `resources/views/layouts/app.blade.php` (enlace condicional)
- `tests/Feature/AuditLogTest.php`, `tests/Feature/AuditLogViewerTest.php` (nuevos)

## Fuentes de verdad a leer antes de empezar
- `docs/requirements.md` (requerimiento funcional 7)
- `docs/backlog.md` (historia 8)
- `.ai/tasks/_closed/AdministracionUsuarios/handoff.md` (patrón de
  middleware `role:` ya establecido)
- `app/Models/Event.php` (estados válidos: pending, approved, rejected,
  completed, canceled)

## Contexto mínimo sugerido
Tamaño **medium**: los ~11 archivos del write-set, sin explorar más allá.
