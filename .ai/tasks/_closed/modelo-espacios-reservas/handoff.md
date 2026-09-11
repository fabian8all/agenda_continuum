# Handoff: modelo-espacios-reservas

**Fecha:** 2026-09-11 · **Rol:** (sin asignar)

## Objetivo
Definir e implementar los modelos Eloquent, migraciones, relaciones y
validaciones de negocio para Escenarios (`Space`) y Reservas (`Booking`),
incluyendo la prevención de colisiones horarias y seeders iniciales (ver
`task.md`).

## Archivos revisados
- `.ai/state/estado-dev.md`, `.ai/state/topics/arquitectura.md`, `.ai/state/topics/resumen.md`
- `AI_COLLABORATION.md`, `AGENTS.md`, `.ai/HANDOFF.md`
- `app/Models/User.php`, `database/factories/UserFactory.php`, `database/seeders/DatabaseSeeder.php`
- Migraciones existentes: `add_role_to_users_table`, `create_spaces_table`, `create_bookings_table`

## Archivos modificados
- `app/Models/Space.php` (nuevo)
- `app/Models/Booking.php` (nuevo)
- `app/Models/User.php` — agrega `role` a `$fillable` y relación `bookings()`
- `database/factories/SpaceFactory.php` (nuevo)
- `database/factories/BookingFactory.php` (nuevo)
- `database/seeders/SpacesTableSeeder.php` (nuevo)
- `database/seeders/DatabaseSeeder.php` — invoca `SpacesTableSeeder`
- `tests/Feature/SpaceBookingModelTest.php` (nuevo, 6 tests)
- `.ai/tasks/modelo-espacios-reservas/execution-plan.md` — pasos 1-5 marcados

## Decisión(es) tomada(s)
- La detección de colisiones horarias vive en un único lugar,
  `Booking::scopeOverlapping()` (`start_time < end AND end_time > start`,
  excluyendo `status = cancelada`, con parámetro opcional para excluir la
  propia reserva al editar). `Space::isAvailable()` delega en ese scope en
  vez de repetir la regla, para tener una sola fuente de verdad.
- Se dejó fuera de esta tarea la validación de aforo (`capacity`) y la
  ventana de anticipación (2h-15 días): son invariantes descritas en
  `arquitectura.md` pero no estaban en el "Incluido en el alcance" de
  `task.md` (que pedía solo modelo de datos + colisión horaria). Evita
  refactors/scope creep oportunista fuera del write-set declarado.

## Suposiciones vigentes
- Se asume que la validación de aforo y de ventana de anticipación se
  implementarán como parte de la tarea de UI/formularios (Livewire), no
  aquí, ya que ahí es donde se conoce el número esperado de alumnos y el
  momento de la solicitud.
- Se asume que `status = 'pendiente'` también debe poder solaparse con
  otras reservas pendientes (solo `cancelada` libera el horario), tal como
  dice la "regla de oro" en `arquitectura.md`; no se pidió confirmación
  explícita de si `pendiente` debería bloquear el rango igual que
  `confirmada` (se implementó que sí bloquea, por ser el comportamiento
  más seguro contra colisiones).

## Validación
- Ejecutada: `sail artisan migrate --force` (6 migraciones OK) · `sail
  artisan test` (8/8 passed, suite completa incluye Unit/Feature previos +
  los 6 tests nuevos) · `sail artisan db:seed --force` (10 `Space` creados
  sin error) · `tools/continuum doctor` (0 problemas, 0 advertencias).
- No ejecutada / pendiente: linter de estilo (Pint); pruebas de UI/Livewire
  (fuera de alcance); validación de aforo y anticipación (fuera de alcance,
  ver arriba).

## Riesgos / dudas abiertas
- `compose.yaml` y `docker-compose.yml` son archivos idénticos duplicados
  en la raíz del repo (ambos untracked); Sail avisa la ambigüedad en cada
  comando. No se tocó por no estar en el write-set de esta tarea — alguien
  debería decidir cuál conservar y borrar el otro.
- Hay un archivo suelto `agenda_escenarios` en la raíz (SQLite, untracked)
  que no corresponde a ninguna ruta de configuración visible en `.env`;
  parece un artefacto accidental de una ejecución previa de migraciones.
  No se tocó por la misma razón.
- El proyecto Laravel completo (`app/`, `routes/`, `config/`, `composer.*`,
  etc.) nunca tuvo un commit inicial — todo sigue apareciendo como
  untracked en `git status`. No es responsabilidad de esta tarea, pero
  conviene resolverlo antes de que crezca más el diff pendiente de commit.

## Siguiente paso recomendado
Con el modelo de datos cerrado, el siguiente trabajo natural es la UI
Livewire explícitamente excluida de esta tarea: `App\Livewire\Bookings\ScheduleCalendar`
(cuadrícula de disponibilidad) y `App\Livewire\Bookings\CreateBookingModal`
(formulario con validación de traslape en tiempo real, más las reglas de
aforo y ventana de anticipación que quedaron pendientes). Antes de arrancarla,
resolver los dos hallazgos de higiene del repo listados arriba (compose
duplicado y archivo `agenda_escenarios` suelto).
