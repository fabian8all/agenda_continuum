# Plan de ejecución: Solicitud de Uso

Solo para tareas `medium`. Marca cada paso al avanzar.

- [x] Paso 1 — Crear Livewire component `ReservationForm` y su vista.
- [x] Paso 2 — Implementar lógica de validación de disponibilidad (sin solapamientos).
- [x] Paso 3 — Guardar registro en `events` con estado `pending` y mostrar mensaje de éxito.
- [x] Paso 4 — Añadir pruebas unitarias/e2e para el formulario.

## Estado actual
Los 4 pasos están completos. `tests/Feature/ReservationFormTest.php` cubre:
creación válida, reseteo del formulario tras éxito, campos requeridos,
`end_time` después de `start_time`, `start_time` no puede ser pasado,
bloqueo por solapamiento con eventos `pending`/`approved`, distinto
escenario en el mismo horario permitido, y que eventos `rejected`/`canceled`
no bloquean disponibilidad. Suite completa corrida en Sail: 16 tests, 43
assertions, sin regresiones.

Se añadieron `database/factories/ScenarioFactory.php` y
`database/factories/EventFactory.php` (no existían; requeridas por
`use HasFactory` en ambos modelos y por las pruebas).

**Tarea `SolicitudUso` completa.** Falta commitear los cambios y decidir la
siguiente tarea (p. ej. crear la tarea de "Gestión de Solicitudes" — aprobar
/rechazar — que quedó fuera de alcance aquí).

Dependencias añadidas fuera del write-set original, necesarias para que el
formulario funcionara:
- `app/Models/Event.php` (no existía; requerido por `Scenario::events()`,
  `CalendarComponent` y `ReservationForm`).
- `config/livewire.php` (publicado con `class_namespace` =
  `App\Http\Livewire`, para que Livewire descubra los componentes que ya
  viven en esa carpeta — sin esto ningún componente del proyecto renderiza).
- `routes/web.php` + `resources/views/solicitudes/nueva.blade.php` (ruta y
  vista mínima para poder probar el componente en el navegador).
