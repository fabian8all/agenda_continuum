# Plan de ejecución: AdministracionUsuarios

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Middleware `role:<roles>` + alias en `bootstrap/app.php`;
      relación `User::administeredScenarios()`; comando
      `users:set-role` para poder promover al primer `coordinador`.
- [x] Paso 2 — `UserManager` (Livewire) + vista: listar, crear/editar
      (nombre, email, rol), asignar escenarios a un `admin`, eliminar.
- [x] Paso 3 — Ruta `/administracion/usuarios` (`role:coordinador`);
      `/solicitudes` pasa a requerir `role:admin,coordinador`; nav
      condicional por rol.
- [x] Paso 4 — `RequestManager` filtra por escenario propio cuando el rol
      es `admin` (en el listado y dentro de cada acción); `coordinador` ve
      todo, como antes.
- [x] Paso 5 — Pruebas de Feature (`UserManagerTest` 9 tests,
      `RequestManagerRoleTest` 4 tests) + ajuste de `RequestManagerTest`
      existente (ahora requiere autenticación).

## Estado actual
Los 5 pasos están completos. Suite completa: 45/45 tests, 111 assertions,
sin regresiones. Verificado manualmente con `curl`: un `coordinador` ve
"Gestión de solicitudes" y "Administración" en la nav y puede crear/editar
usuarios; un `docente` recibe 403 en `/solicitudes` y
`/administracion/usuarios` (pero sigue pudiendo crear solicitudes en
`/solicitudes/nueva`) y no ve esos enlaces en la nav.

**Tarea completa.** Falta commitear y cerrarla con `continuum task close`.
