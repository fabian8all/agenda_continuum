# Plan de ejecución: GestionSolicitudes

Solo para tareas `medium`. Marca cada paso al avanzar.

- [x] Paso 1 — Crear Livewire component `RequestManager` + vista: lista de
      solicitudes `pending` con botones Aprobar/Rechazar.
- [x] Paso 2 — Agregar a `RequestManager` la sección de eventos `approved`
      con `end_time` pasada: botones Marcar terminado / Cancelar.
- [x] Paso 3 — Ruta `/solicitudes`, vista `solicitudes/gestion.blade.php` y
      enlace en la navegación (`layouts/app.blade.php`).
- [x] Paso 4 — Pruebas de Feature: transiciones válidas (pending→approved,
      pending→rejected, approved+pasado→completed, approved+pasado→canceled)
      e inválidas (no se puede cerrar un evento futuro o aún pendiente).

## Estado actual
Los 4 pasos están completos. `tests/Feature/RequestManagerTest.php` (9
tests) cubre listados y las 4 transiciones válidas + 3 inválidas. Suite
completa: 25/25 tests, 59 assertions, sin regresiones. Verificado también
en `/solicitudes` vía curl con datos reales (sembrados a mano en la BD de
Sail, no en seeders). **Tarea completa**, falta commitear y cerrarla con
`continuum task close`.
