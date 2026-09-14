# Tarea: GestionSolicitudes

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Permitir revisar las solicitudes de uso pendientes y decidir sobre ellas
(aprobar/rechazar), y cerrar los eventos ya aprobados marcándolos como
terminados o cancelados — requerimiento funcional 4 (`docs/requirements.md`)
e historias 4 y 5 de `docs/backlog.md`.

## Incluido en el alcance
- Listado de solicitudes con estado `pending`, con acciones **Aprobar** y
  **Rechazar** que cambian el estado del `Event` a `approved`/`rejected`.
- Para eventos `approved` cuya `end_time` ya pasó: acciones **Marcar
  terminado** y **Cancelar** (cambian el estado a `completed`/`canceled`).
- Mensaje de confirmación en el portal tras cada acción (mismo patrón que
  `ReservationForm::$successMessage`).
- Ruta y enlace de navegación (`/solicitudes`) para poder revisarlo en el
  navegador, igual que se hizo para las demás vistas.
- Pruebas de Feature para las transiciones de estado válidas e inválidas.

## Explícitamente fuera de alcance
- **Envío real de correos electrónicos.** El requerimiento pide notificar
  "por correo o mediante el portal"; se implementa solo la variante de
  portal. El esquema actual de `events` no tiene una relación con el
  usuario solicitante (no se capturó "quién pide" en `SolicitudUso`), así
  que no hay a quién enviarle el correo sin antes resolver ese gap de
  datos — se deja como decisión de producto para otra tarea.
- **Restricción por "administrador del escenario".** La historia dice "mis
  escenarios", pero no hay autenticación ni sesión de usuario todavía
  (Autenticación Federada es la prioridad 3, posterior a esta). Cualquiera
  puede ver y decidir sobre cualquier solicitud por ahora, igual que el
  resto de las vistas del proyecto no tienen control de acceso aún.
- Auditoría/historial de quién aprobó o rechazó cada solicitud (prioridad 6,
  "Auditoría y Registro", explícitamente posterior en `resumen.md`).

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `app/Http/Livewire/RequestManager.php` (nuevo)
- `resources/views/livewire/request-manager.blade.php` (nuevo)
- `resources/views/solicitudes/gestion.blade.php` (nuevo)
- `routes/web.php` (agrega `/solicitudes`)
- `resources/views/layouts/app.blade.php` (agrega el enlace de navegación)
- `tests/Feature/RequestManagerTest.php` (nuevo)

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `docs/requirements.md` (requerimiento funcional 4)
- `docs/backlog.md` (historias 4 y 5)
- `.ai/tasks/_closed/SolicitudUso/handoff.md` (decisiones y supuestos de la
  tarea anterior sobre `Event`/`Scenario`)

## Contexto mínimo sugerido
Tamaño **medium**: revisar `app/Models/Event.php`, `app/Http/Livewire/ReservationForm.php`
y `resources/views/livewire/reservation-form.blade.php` como referencia de
convenciones (Livewire + validación + mensaje de éxito), sin explorar el
resto del código.
