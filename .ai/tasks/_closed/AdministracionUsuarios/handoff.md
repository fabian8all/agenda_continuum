# Handoff: AdministracionUsuarios

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
CRUD de usuarios y asignación de roles/escenarios, usando esos roles para
cerrar el hueco de autorización documentado desde `GestionSolicitudes`
(cualquier usuario logueado podía aprobar/rechazar cualquier solicitud).
Ver `task.md` para el mapeo de roles confirmado con el usuario.

## Archivos revisados
- `docs/requirements.md` (§2 stakeholders), `docs/backlog.md` (historia 6)
- `.ai/tasks/_closed/GestionSolicitudes/handoff.md`,
  `.ai/tasks/_closed/AutenticacionFederada/handoff.md`
- `app/Models/Scenario.php`, `app/Http/Livewire/RequestManager.php`

## Archivos modificados/creados
- `app/Http/Middleware/EnsureRole.php` (nuevo) + alias `role` en
  `bootstrap/app.php`.
- `app/Models/User.php` — `administeredScenarios(): HasMany`.
- `app/Console/Commands/SetUserRole.php` (nuevo) — `users:set-role
  {email} {role}`.
- `app/Http/Livewire/UserManager.php` + `resources/views/livewire/user-manager.blade.php`
  (nuevos) — CRUD de usuarios, asignación de escenarios a un `admin`.
- `resources/views/administracion/usuarios.blade.php` (nuevo).
- `routes/web.php` — `/administracion/usuarios` (`role:coordinador`);
  `/solicitudes` ahora exige `role:admin,coordinador`.
- `resources/views/layouts/app.blade.php` — enlaces de nav condicionales
  por rol.
- `app/Http/Livewire/RequestManager.php` — `scopeToOwnScenarios()`
  filtra por `scenarios.admin_id` cuando el rol es `admin`; se aplica
  tanto en `refreshLists()` como dentro de `transition()`.
- `tests/Feature/UserManagerTest.php` (9 tests),
  `tests/Feature/RequestManagerRoleTest.php` (4 tests), y ajuste de
  `tests/Feature/RequestManagerTest.php` (ahora hace `actingAs` un
  `coordinador` en `setUp()`, porque el componente ya requiere un usuario
  autenticado).

## Decisión(es) tomada(s)
- **Mapeo de roles** (`docente`=Solicitante, `admin`=Administrador de
  Escenario, `coordinador`=Administrador General): confirmado
  explícitamente con el usuario antes de implementar, por ser una decisión
  de autorización difícil de revertir barata. Ver "Decisión de mapeo de
  roles" en `task.md`.
- **Autorización validada dos veces** (listado y cada acción): un `admin`
  solo ve sus propios eventos en `pendingEvents`/`closableEvents`, pero
  además `transition()` vuelve a aplicar el mismo filtro al buscar el
  `Event` por id — así una llamada directa a `approve($id)` con un id
  ajeno (saltándose la UI) también se rechaza, no solo se oculta de la
  lista.
- **No se reemplazó el enum `role`** por una tabla `roles` ni
  `spatie/laravel-permission` (que sugiere `docs/backlog.md`): 3 roles
  fijos ya cubren el dominio actual; cambiar de arquitectura no se pidió y
  habría sido scope creep.
- **Comando `users:set-role`**: sin él, nadie podría llegar nunca a
  `/administracion/usuarios`, porque el aprovisionamiento SAML (tarea
  `AutenticacionFederada`) siempre crea usuarios nuevos con rol `docente`.
  Es la única vía de bootstrap para el primer `coordinador`.
- **Contraseña aleatoria para usuarios creados desde la UI**: igual que
  los usuarios aprovisionados por SAML, nunca se autentican por
  contraseña, así que no se construyó ningún flujo de "enviar contraseña"
  ni reseteo.

## Suposiciones vigentes
- Se asume que un `coordinador` no necesita "administrar" escenarios
  explícitamente (ya ve/gestiona todo); el multi-select de escenarios en
  el formulario solo se muestra cuando el rol elegido es `admin`.
- Se asume que borrar un usuario no necesita advertencia especial sobre
  sus solicitudes históricas (`events.requester_id` se pone en `null` vía
  `onDelete('set null')`, ya definido en la tarea `SolicitanteEvent`); no
  se pidió conservar esa relación de otra forma.

## Validación
- Ejecutada: `sail artisan test` (45/45 passed, 111 assertions, incluye
  los 13 tests nuevos sin regresiones) · verificación manual con `curl`:
  se promovió un usuario a `coordinador` con `users:set-role`, se
  confirmó que ve los enlaces de nav y puede acceder a
  `/administracion/usuarios`; se degradó el mismo usuario a `docente` y
  se confirmó 403 en `/solicitudes` y `/administracion/usuarios` (pero
  200 en `/solicitudes/nueva`) y que la nav ya no muestra esos enlaces;
  se limpiaron los datos de prueba de la BD de Sail al terminar.
- No ejecutada / pendiente: revisión visual humana en navegador real del
  nuevo formulario de usuarios.

## Riesgos / dudas abiertas
- Ninguno nuevo. El único usuario `coordinador` en cualquier ambiente
  nuevo debe crearse a mano con `php artisan users:set-role` — vale la
  pena documentar este paso en cualquier guía de despliegue futura.

## Siguiente paso recomendado
Con las 5 épicas fundamentales del MVP completas (Escenarios, Calendario,
Solicitud, Gestión de Solicitudes, Autenticación Federada,
Administración de Usuarios), el siguiente elemento de la prioridad MVP en
`resumen.md` es **Acceso Público** (prioridad 5) — aunque en la práctica
ya está satisfecho (catálogo/calendario/inicio son públicos desde
`AutenticacionFederada`); conviene revisar si falta algo explícito de esa
historia antes de pasar a **Auditoría** (prioridad 6).
