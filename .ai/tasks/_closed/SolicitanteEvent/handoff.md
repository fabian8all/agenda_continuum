# Handoff: SolicitanteEvent

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Agregar la relación entre el usuario solicitante y su `Event`, gap de datos
detectado en `.ai/tasks/_closed/GestionSolicitudes/handoff.md` (ver
`task.md`).

## Archivos modificados/creados
- `database/migrations/2026_09_14_161400_add_requester_id_to_events_table.php`
  (nuevo) — `requester_id` nullable, FK a `users`, `onDelete('set null')`.
- `app/Models/Event.php` — `requester_id` en `$fillable`,
  `requester(): belongsTo(User::class, 'requester_id')`.
- `app/Models/User.php` — `eventRequests(): hasMany(Event::class, 'requester_id')`.
- `app/Http/Livewire/ReservationForm.php` — guarda `requester_id` =>
  `auth()->id()` al crear el `Event`.
- `tests/Feature/ReservationFormTest.php` — 2 tests nuevos: la relación se
  guarda correctamente cuando hay un usuario autenticado
  (`Livewire::actingAs`), y queda `null` para un envío sin autenticar.

## Decisión(es) tomada(s)
- `requester_id` es **nullable**, no `NOT NULL`: no existe autenticación
  real todavía (Autenticación Federada es la prioridad 3, posterior), así
  que forzar el campo habría roto el flujo actual de `ReservationForm`
  (cualquiera puede solicitar sin loguearse). Queda listo para dejar de
  ser nullable el día que haya login obligatorio en esa vista.
- No se tocó `EventFactory`: la relación `requester()` ya definida en
  `Event` es suficiente para que `Event::factory()->for($user, 'requester')`
  funcione en pruebas sin necesitar un cambio en `definition()`.
- `onDelete('set null')` en vez de `cascade`, igual que `scenarios.admin_id`:
  borrar un usuario no debe borrar el historial de eventos que solicitó.

## Suposiciones vigentes
- Se asume que cuando exista Autenticación Federada, `ReservationForm`
  seguirá usando `auth()->id()` sin cambios — la tarea de auth solo
  necesita loguear al usuario antes de llegar a esa vista.
- No se tocó `RequestManager` ni sus vistas: mostrar el nombre del
  solicitante en la lista de gestión es una mejora natural pero no estaba
  en el alcance de esta tarea (que era solo el dato, no su presentación).

## Validación
- Ejecutada: `sail artisan migrate --force` (1 migración OK) · `sail
  artisan test` (27/27 passed, 64 assertions, incluye los 2 tests nuevos
  sin regresiones) · verificado con `curl` que `/solicitudes/nueva` sigue
  respondiendo 200 sin usuario autenticado (el caso real actual, dado que
  no hay login) · confirmado con `Schema::getColumnListing('events')` que
  la columna quedó creada.
- No ejecutada / pendiente: mostrar el solicitante en `RequestManager`
  (fuera de alcance, ver arriba).

## Riesgos / dudas abiertas
- Ninguno nuevo. El riesgo de falta de autenticación/control de acceso ya
  estaba documentado en el handoff de `GestionSolicitudes` y sigue vigente
  sin cambios — esta tarea no lo agrava ni lo resuelve, solo prepara el
  dato para cuando se resuelva.

## Siguiente paso recomendado
Autenticación Federada (SimpleSAML) — prioridad 3 en `resumen.md` — es
ahora el bloqueador real tanto para el control de acceso pendiente como
para poder implementar notificaciones por correo al solicitante (que ya
tiene el dato que le faltaba, gracias a esta tarea).
