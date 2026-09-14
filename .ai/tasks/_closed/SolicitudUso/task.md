# Tarea: Solicitud de Uso

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Implementar el formulario y la lógica de negocio para que los profesores puedan solicitar el uso de un escenario (aula, laboratorio, taller, auditorio).

## Incluido en el alcance
- Formulario de solicitud con campos: escenario, fecha/hora inicio, fecha/hora fin, descripción.
- Validación de disponibilidad (sin solapamientos).
- Creación de registro en la tabla `events` con estado **pendiente**.
- Notificación al solicitante (mensaje en la UI).

## Explícitamente fuera de alcance
- Aprobar/rechazar la solicitud (se maneja en la siguiente tarea de gestión).
- Envío de correos (se añadirá en la tarea de gestión).

## Write-set (archivos que se espera tocar)
- `app/Http/Livewire/ReservationForm.php`
- `resources/views/livewire/reservation-form.blade.php`
- `app/Http/Controllers/ReservationController.php` (si se usa MVC)
- `database/migrations/2026_09_14_XXXXXX_create_events_table.php` (ya existe)

## Fuentes de verdad a leer antes de empezar
- `.ai/state/topics/resumen.md`
- `docs/backlog.md` (épica **Solicitud de Uso**)
- `docs/requirements.md` (requerimientos funcionales 3 y 4)

## Contexto mínimo sugerido
Según el tamaño **medium**, se puede revisar el módulo `app/Models/Event.php` y la migración `events` antes de comenzar.
