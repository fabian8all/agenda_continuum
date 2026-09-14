# Handoff: GestionSolicitudes

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Permitir revisar las solicitudes de uso pendientes y decidir sobre ellas
(aprobar/rechazar), y cerrar los eventos ya aprobados marcándolos como
terminados o cancelados (ver `task.md`).

## Archivos revisados
- `.ai/tasks/_closed/SolicitudUso/handoff.md` (decisiones y supuestos sobre
  `Event`/`Scenario` de la tarea anterior)
- `app/Models/Event.php`, `app/Http/Livewire/ReservationForm.php`,
  `resources/views/livewire/reservation-form.blade.php` (convenciones)
- `docs/requirements.md` (requerimiento funcional 4), `docs/backlog.md`
  (historias 4 y 5)

## Archivos modificados/creados
- `app/Http/Livewire/RequestManager.php` (nuevo) — lista `pending` con
  aprobar/rechazar, y lista `approved` con `end_time` pasada con marcar
  terminado/cancelar. Cada acción valida el estado origen (y que ya haya
  pasado, cuando aplica) antes de aplicar la transición.
- `resources/views/livewire/request-manager.blade.php` (nuevo).
- `resources/views/solicitudes/gestion.blade.php` (nuevo).
- `routes/web.php` — ruta `/solicitudes` (`solicitudes.gestion`).
- `resources/views/layouts/app.blade.php` — enlace de navegación
  "Gestión de solicitudes".
- `tests/Feature/RequestManagerTest.php` (nuevo, 9 tests).

## Decisión(es) tomada(s)
- Se siguió la letra de la historia 5 de `docs/backlog.md`: tanto "marcar
  terminado" como "cancelar" solo están disponibles para eventos
  `approved` cuya `end_time` ya pasó (no se distinguió un caso de
  "cancelar un evento aprobado futuro", que la historia no contempla).
- Las transiciones inválidas (estado origen incorrecto, o evento aún
  futuro) no lanzan excepción ni validación de formulario: se resuelven
  con un `errorMessage` genérico en el componente, porque no son errores
  de un usuario llenando un formulario sino de una fila que cambió de
  estado entre que se cargó la lista y se hizo clic (concurrencia normal
  en una lista compartida).
- No se agregó control de acceso por escenario/administrador ni envío de
  correo — ver "Explícitamente fuera de alcance" en `task.md`, ambos
  bloqueados por gaps reales de infraestructura (no hay autenticación ni
  relación `Event` → usuario solicitante), no por decisión de producto.

## Suposiciones vigentes
- Se asume que cualquier persona puede ver y decidir sobre cualquier
  solicitud por ahora (sin login), igual que el resto de las vistas del
  proyecto. Esto deja de ser válido en cuanto se implemente Autenticación
  Federada (prioridad 3) y habrá que revisar `RequestManager` para filtrar
  por escenarios del administrador autenticado.
- Se asume que "notificación... mediante el portal" se satisface con el
  `successMessage` que ve quien aprueba/rechaza (el administrador), no con
  una notificación al solicitante original — porque no hay forma de saber
  quién fue el solicitante con el esquema actual de `events`.

## Validación
- Ejecutada: `sail artisan test` (25/25 passed, 59 assertions, incluye los
  9 tests nuevos de `RequestManagerTest` sin regresiones) · verificación
  manual en `/solicitudes` vía `curl` con datos reales (se agregó un
  evento aprobado con fecha pasada a los datos de ejemplo ya sembrados en
  la tarea anterior, directamente en la BD de Sail; no está en ningún
  seeder ni se commiteó).
- No ejecutada / pendiente: linter de estilo (Pint); accesibilidad WCAG 2.1
  AA; control de acceso (fuera de alcance, ver arriba).

## Riesgos / dudas abiertas
- **Gap de datos real:** `events` no tiene una columna/relación para el
  usuario solicitante. Cualquier tarea futura de notificaciones por correo
  o de "mis solicitudes" (vista del profesor) va a necesitar una migración
  para agregarla — vale la pena resolverlo antes de escribir más lógica
  que asuma que no existe.
- Sigue sin control de acceso ninguna de las vistas del proyecto
  (catálogo, calendario, solicitud, gestión) — es esperado hasta que se
  implemente Autenticación Federada, pero significa que ahora mismo
  cualquier visitante puede aprobar/rechazar/cerrar solicitudes desde
  `/solicitudes`. No es un problema de esta tarea en particular, pero
  conviene que quien trabaje la Autenticación Federada revise las 4 rutas.

## Siguiente paso recomendado
Con la épica 1 (Escenarios/Calendario/Solicitud) y la épica 2 (Gestión de
Solicitudes) completas, el siguiente elemento de la prioridad MVP en
`resumen.md` es **Autenticación Federada** (SimpleSAML) — o, si se prefiere
resolver primero el gap de datos detectado arriba, una tarea corta para
agregar la relación solicitante ↔ `Event` antes de construir login sobre
un esquema que la necesita.
