# Handoff: SolicitudUso

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Implementar el formulario y la lógica de negocio para que los profesores
puedan solicitar el uso de un escenario (aula, laboratorio, taller,
auditorio), validando disponibilidad y dejando la solicitud en estado
`pending` (ver `task.md`).

## Archivos revisados
- `.ai/state/topics/resumen.md`, `docs/backlog.md`, `docs/requirements.md`
  (requerimientos funcionales 3 y 4)
- `app/Models/Scenario.php`, migraciones `create_scenarios_table` /
  `create_events_table` (ya existían, sin commitear)
- `app/Http/Livewire/ScenarioCatalog.php` y `CalendarComponent.php` (scaffold
  previo, también sin commitear)

## Archivos modificados/creados
- `app/Http/Livewire/ReservationForm.php` (nuevo) — formulario Livewire con
  validación y creación del `Event`.
- `resources/views/livewire/reservation-form.blade.php` (nuevo).
- `app/Models/Event.php` (nuevo) — no existía; lo requerían
  `Scenario::events()`, `CalendarComponent` y el propio `ReservationForm`.
- `config/livewire.php` (nuevo, publicado desde el paquete) — se fijó
  `class_namespace` = `App\Http\Livewire` para que Livewire descubra los
  componentes que ya vivían en esa carpeta (sin esto, ninguno renderizaba).
- `database/factories/ScenarioFactory.php` y `EventFactory.php` (nuevos) —
  requeridas por `use HasFactory` en ambos modelos y por las pruebas.
- `tests/Feature/ReservationFormTest.php` (nuevo, 8 tests).
- `routes/web.php` — rutas `/`, `/escenarios`, `/calendario`,
  `/solicitudes/nueva`.
- `resources/views/layouts/app.blade.php`, `resources/views/home.blade.php`,
  `resources/views/escenarios/index.blade.php`,
  `resources/views/calendario/index.blade.php`,
  `resources/views/solicitudes/nueva.blade.php` (nuevos/reescritos) —
  navegación mínima para poder revisar la funcionalidad en el navegador.
- `.ai/tasks/SolicitudUso/execution-plan.md` — pasos 1-4 marcados.

## Decisión(es) tomada(s)
- El chequeo de solapamiento vive en `ReservationForm::submit()` (no en el
  modelo `Event`, a diferencia de `Space::isAvailable()` de la tarea previa)
  porque el `task.md` de esta tarea acotaba el alcance al formulario y su
  lógica; no se tocó `Event`/`Scenario` con un scope reutilizable para no
  ampliar el write-set declarado.
- Solo los eventos en estado `pending` o `approved` bloquean el horario;
  `rejected` y `canceled` no lo hacen (mismo criterio que
  `Booking::scopeOverlapping()` en la tarea `modelo-espacios-reservas`, que
  excluye `cancelada`).
- `start_time` debe ser posterior a "ahora" (`after:now`) — no estaba escrito
  explícitamente en `task.md`, pero se asumió como regla mínima razonable
  para no permitir solicitudes retroactivas; no se implementó la ventana de
  anticipación 2h-15 días de `arquitectura.md` por seguir fuera del alcance
  declarado (igual que en la tarea anterior).

## Suposiciones vigentes
- La aprobación/rechazo y el envío de correos quedan, como estaba explícito
  en `task.md`, para la tarea de "Gestión de Solicitudes" (aún no creada
  como tarea Continuum).
- Se asume que la UI de navegación (`/`, `/escenarios`, `/calendario`)
  agregada para poder revisar en el navegador es provisional/mínima — no
  hay diseño ni accesibilidad WCAG 2.1 AA todavía (requerimiento no
  funcional 7, explícitamente marcado como iterativo en `resumen.md`).

## Validación
- Ejecutada: `sail artisan migrate --force` (2 migraciones OK) · `sail
  artisan test` (16/16 passed, 43 assertions, incluye los 8 tests nuevos de
  `ReservationFormTest` sin regresiones en `SpaceBookingModelTest`) ·
  verificación manual con `Livewire::test()` vía tinker (creación válida +
  rechazo de solapamiento) · las 4 rutas (`/`, `/escenarios`, `/calendario`,
  `/solicitudes/nueva`) probadas con `curl` devolviendo 200 y contenido real
  (se sembraron 3 `Scenario` y 3 `Event` de ejemplo directamente en la BD de
  Sail para la revisión visual; no están en ningún seeder ni se commitearon).
- No ejecutada / pendiente: linter de estilo (Pint); accesibilidad WCAG 2.1
  AA; pruebas de la navegación (`/`, `/escenarios`, `/calendario`) — solo se
  verificó que renderizan, no se agregaron tests de Feature para ellas por
  ser scaffolding de revisión, no parte del alcance de esta tarea.

## Riesgos / dudas abiertas
- `compose.yaml`, `.ai/HANDOFF.md` y `.ai/state/topics/resumen.md` seguían
  modificados sin commitear desde antes de esta tarea; no se tocaron ni se
  incluyeron en los commits de esta tarea por no ser parte de su write-set.
- `.ai/tasks/Backlog/task.md` sigue siendo una plantilla vacía sin llenar
  (no es una tarea real); alguien debería completarla o descartarla.
- La UI de navegación agregada (`/`, `/escenarios`, `/calendario`) no estaba
  en el "Incluido en el alcance" original de `task.md`; se agregó a pedido
  explícito del usuario en esta misma sesión para poder revisar en el
  navegador. Vale la pena decidir si esta navegación mínima debe formalizarse
  como su propia tarea (con diseño, accesibilidad, etc.) más adelante.

## Siguiente paso recomendado
Crear la tarea "Gestión de Solicitudes" (aprobar/rechazar, notificaciones,
cambio de estado a terminado/cancelado — requerimiento funcional 4), que es
el siguiente elemento de la prioridad MVP en `resumen.md` después de esta
tarea.
