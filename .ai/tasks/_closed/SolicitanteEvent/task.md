# Tarea: SolicitanteEvent

**Creada:** 2026-09-14 · **Tamaño:** small · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Agregar la relación entre el usuario solicitante y su `Event`, gap de datos
detectado y documentado en `.ai/tasks/_closed/GestionSolicitudes/handoff.md`:
sin ella no se puede notificar por correo a quien pidió el escenario ni
mostrar "mis solicitudes".

## Incluido en el alcance
- Migración que agrega `requester_id` (nullable, FK a `users`, `set null`
  en borrado) a `events`.
- `Event::requester()` (`belongsTo(User::class)`) y
  `User::eventRequests()` (`hasMany(Event::class, 'requester_id')`).
- `ReservationForm` guarda `requester_id` a partir de `auth()->id()` (será
  `null` mientras no exista autenticación real — ver fuera de alcance).
- Actualizar `EventFactory` para poder asociar un `requester` opcional.
- Pruebas: la relación funciona en ambos sentidos, y `ReservationForm`
  guarda el `requester_id` correcto cuando hay un usuario autenticado.

## Explícitamente fuera de alcance
- Autenticación Federada / login real (prioridad 3 en `resumen.md`); esta
  tarea solo prepara el dato para cuando exista.
- Notificaciones por correo al solicitante (quedan desbloqueadas por esta
  tarea, pero se implementan aparte).
- Vista "mis solicitudes" para el profesor.
- Requerir `requester_id` (`NOT NULL`): se deja nullable a propósito porque
  hoy no hay forma de garantizar que siempre haya un usuario autenticado.

## Write-set (archivos que se espera tocar)
- `database/migrations/2026_09_14_XXXXXX_add_requester_id_to_events_table.php` (nuevo)
- `app/Models/Event.php`
- `app/Models/User.php`
- `app/Http/Livewire/ReservationForm.php`
- `database/factories/EventFactory.php`
- `tests/Feature/ReservationFormTest.php`

## Fuentes de verdad a leer antes de empezar
- `.ai/tasks/_closed/GestionSolicitudes/handoff.md` (sección "Riesgos /
  dudas abiertas", donde se detectó este gap)
- `app/Models/Scenario.php` (patrón ya usado para `admin_id` nullable con
  `onDelete('set null')`)

## Contexto mínimo sugerido
Tamaño **small**: 1-3 archivos concretos (migración, modelo, formulario),
sin explorar carpetas completas.
