# Plan de ejecución: AuditoriaRegistro

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Tabla `audit_logs`, modelo `AuditLog`, `EventObserver`
      (acciones `created`/`approved`/`rejected`/`completed`/`canceled`)
      registrado en `AppServiceProvider`.
- [x] Paso 2 — `AuditLogViewer` (Livewire, filtros por acción y rango de
      fechas persistidos en la URL) en `/administracion/auditoria`.
- [x] Paso 3 — `AuditLogExportController` (CSV por streaming, mismos
      filtros por query string) en `/administracion/auditoria/exportar`.
- [x] Paso 4 — Ambas rutas protegidas con `role:coordinador`; enlace
      "Auditoría" en la nav solo para `coordinador`.
- [x] Paso 5 — Pruebas de Feature (`AuditLogTest` 7 tests,
      `AuditLogViewerTest` 7 tests).

## Estado actual
Los 5 pasos están completos. Suite completa: 59/59 tests, 129 assertions,
sin regresiones. Verificado manualmente con `curl`: se promovió un
usuario a `coordinador`, se generaron eventos y transiciones de estado
reales, se confirmó que aparecen en `/administracion/auditoria`, que el
filtro `?action=approved` (persistido en la URL) reduce la tabla
correctamente, y que `/administracion/auditoria/exportar` devuelve un CSV
válido con las columnas esperadas. Se confirmó también que borrar un
usuario deja `audit_logs.user_id` en `null` (vía `onDelete('set null')`)
en vez de romper el registro histórico. Datos de prueba limpiados al
terminar.

**Tarea completa.** Falta commitear y cerrarla con `continuum task close`.
