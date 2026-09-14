# Tarea: AdministracionUsuarios

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
CRUD de usuarios y asignación de roles/escenarios (requerimiento funcional
5, historia 6 de `docs/backlog.md`), y usar esos roles para cerrar el
hueco de autorización documentado desde `GestionSolicitudes`: hoy
cualquier usuario logueado (sin importar su rol) puede aprobar/rechazar
cualquier solicitud en `/solicitudes`.

## Decisión de mapeo de roles (confirmada con el usuario)
El enum `role` de `users` (`docente`, `admin`, `coordinador`, del scaffold
original) se mapea a los 3 roles con acceso de
`docs/requirements.md` §2:
- `docente` → **Solicitante**: crea solicitudes, sin acceso a gestión.
- `admin` → **Administrador de Escenario**: gestiona solo las solicitudes
  de los escenarios que tiene asignados (`scenarios.admin_id`).
- `coordinador` → **Administrador General**: acceso total (CRUD de
  usuarios, gestiona solicitudes de todos los escenarios).

## Incluido en el alcance
- Middleware `role:<roles...>` (`App\Http\Middleware\EnsureRole`) para
  proteger rutas por rol.
- `App\Http\Livewire\UserManager` + vista: listar usuarios, crear/editar
  (nombre, email, rol), asignar uno o varios escenarios a un usuario con
  rol `admin` (multi-select sobre `scenarios.admin_id`), eliminar usuario.
- Ruta `/administracion/usuarios`, protegida con `verify.auth` +
  `role:coordinador`; enlace en la navegación solo visible para
  `coordinador`.
- `/solicitudes` (`RequestManager`) pasa a requerir `role:admin,coordinador`
  (un `docente` ya no puede entrar). Dentro del componente: un `admin` solo
  ve/gestiona eventos de sus escenarios asignados; `coordinador` ve todos
  (como hoy). Se valida también dentro de cada acción
  (approve/reject/complete/cancel), no solo en el listado, para que un
  `admin` no pueda actuar sobre un evento ajeno llamando el método
  directamente.
- Comando `php artisan users:set-role {email} {role}` para poder
  promover al primer `coordinador` — sin él, nadie puede llegar nunca a
  `/administracion/usuarios` (el aprovisionamiento SAML siempre crea
  usuarios con rol `docente` por defecto).
- Pruebas de Feature para el middleware de rol, el CRUD, y el filtrado por
  escenario en `RequestManager`.

## Explícitamente fuera de alcance
- Reemplazar el enum `role` por una tabla `roles`/`spatie/laravel-permission`
  (lo sugiere `docs/backlog.md` en "Tareas Técnicas", pero es un cambio de
  arquitectura mayor no pedido explícitamente; el enum actual ya cubre los
  3 roles necesarios).
- Eliminar usuarios en cascada con sus solicitudes/auditoría: fuera de
  alcance de auditoría (prioridad 6, posterior).
- Login por contraseña para usuarios creados manualmente desde esta UI:
  siguen autenticándose solo vía SAML (simulador); se les asigna una
  contraseña aleatoria que nunca se usa, igual que a los aprovisionados
  automáticamente.

## Write-set (archivos que se espera tocar)
- `app/Http/Middleware/EnsureRole.php` (nuevo)
- `bootstrap/app.php` (alias `role`)
- `app/Models/User.php` (relación `administeredScenarios()`)
- `app/Http/Livewire/UserManager.php` (nuevo)
- `resources/views/livewire/user-manager.blade.php` (nuevo)
- `resources/views/administracion/usuarios.blade.php` (nuevo)
- `routes/web.php`
- `resources/views/layouts/app.blade.php` (enlace condicional)
- `app/Http/Livewire/RequestManager.php` (filtrado por escenario)
- `app/Console/Commands/SetUserRole.php` (nuevo)
- `tests/Feature/UserManagerTest.php`, `tests/Feature/RequestManagerRoleTest.php` (nuevos)

## Fuentes de verdad a leer antes de empezar
- `docs/requirements.md` (§2 stakeholders, requerimiento 5)
- `docs/backlog.md` (historia 6)
- `.ai/tasks/_closed/GestionSolicitudes/handoff.md`,
  `.ai/tasks/_closed/AutenticacionFederada/handoff.md` (el hueco de
  autorización que esta tarea cierra)
- `app/Models/Scenario.php`, `app/Http/Livewire/RequestManager.php`
  (código existente a modificar)

## Contexto mínimo sugerido
Tamaño **medium**: los ~11 archivos del write-set, sin explorar más allá.
