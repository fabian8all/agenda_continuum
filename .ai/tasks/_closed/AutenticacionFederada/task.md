# Tarea: AutenticacionFederada

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Implementar el login federado (SimpleSAML) — requerimiento funcional/no
funcional de `docs/requirements.md` — replicando el patrón ya usado y
probado en el proyecto hermano `../redi/redi-app` (paquete
`aacotroneo/laravel-saml2`, middleware `verify.auth` con modo simulador
controlado por `.env`), y usarlo para proteger las vistas de acción
(`/solicitudes/nueva`, `/solicitudes`) que hoy son públicas.

## Incluido en el alcance
- Paquete `aacotroneo/laravel-saml2` instalado y configurado (mismo que
  `redi`), con un IdP `test` apuntando al host de prueba de DGRE
  (`SAML2_TEST_IDP_HOST=https://dgre2.ucol.mx/simplesaml`, mismos
  valores que `redi/.env.example`).
- `config/saml.php` (nuevo, específico de este proyecto — no existía en
  `redi`) para leer las variables `SAML_SIMULATOR*` vía `config()` en vez
  de `env()` directo, evitando el problema conocido de Laravel de que
  `env()` fuera de archivos de config se rompe con `config:cache`.
- `App\Http\Middleware\VerifyAuthSaml`: si `SAML_SIMULATOR=true`, simula
  los atributos SAML vía `.env`/`config/saml.php` (igual que `redi`); si
  no, delega en `Saml2Auth` contra el IdP real. Aprovisiona/actualiza el
  `User` local por email (`firstOrCreate`, simplificado respecto a `redi`
  porque este proyecto no tiene tabla `roles` — ver decisiones).
- `App\Http\Controllers\Auth\AuthController` con `login()`/`logout()`.
- Rutas `/login`, `/logout`.
- Middleware `verify.auth` aplicado a `/solicitudes/nueva` y
  `/solicitudes` (las dos vistas de acción); catálogo, calendario e
  inicio siguen públicos.
- Navegación (`layouts/app.blade.php`) muestra el nombre del usuario
  autenticado + enlace de cierre de sesión, o "Iniciar sesión" si es
  invitado.
- Pruebas de Feature con `SAML_SIMULATOR` activo.

## Explícitamente fuera de alcance
- **Validar el flujo SAML real contra el IdP de DGRE.** No hay forma de
  probarlo desde este entorno sin que el `entityId` de esta app esté
  registrado del lado del IdP — queda wireado igual que `redi` (mismo
  paquete, mismo patrón de config) pero sin verificación end-to-end. Ver
  "Riesgos" en el handoff.
- **Roles/permisos ni administración de usuarios** (prioridad 4,
  posterior). El usuario aprovisionado automáticamente recibe el rol por
  defecto de la columna `role` (`docente`); no hay UI para cambiarlo.
- **Restricción por "administrador del escenario"** en `RequestManager`
  (documentado como pendiente desde `GestionSolicitudes`): ahora se exige
  estar logueado, pero cualquier usuario logueado puede seguir
  aprobando/rechazando cualquier solicitud — eso requiere roles (fuera de
  alcance de esta tarea).
- Cambiar el paquete `aacotroneo/laravel-saml2` por algo más moderno: se
  usa el mismo que `redi` a propósito, por consistencia entre proyectos
  DGRE y porque ya está probado en producción ahí.

## Write-set (archivos que se espera tocar)
- `composer.json` / `composer.lock` (nueva dependencia)
- `config/saml.php` (nuevo)
- `config/saml2_settings.php` (nuevo, copiado/adaptado de `redi`)
- `config/saml2/test_idp_settings.php` (nuevo, copiado/adaptado de `redi`)
- `app/Http/Middleware/VerifyAuthSaml.php` (nuevo)
- `app/Http/Controllers/Auth/AuthController.php` (nuevo)
- `bootstrap/app.php` (alias del middleware `verify.auth`)
- `routes/web.php` (`/login`, `/logout`, proteger `/solicitudes*`)
- `resources/views/layouts/app.blade.php` (estado de sesión en la nav)
- `.env.example` (documentar las variables `SAML_*`)
- `tests/Feature/AuthSamlTest.php` (nuevo)

## Fuentes de verdad a leer antes de empezar
- `../redi/redi-app/app/Http/Middleware/VerifyAuthSaml.php`,
  `AuthController.php`, `config/saml2_settings.php`,
  `config/saml2/test_idp_settings.php`, `.env.example` (patrón de
  referencia, mismo grupo DGRE)
- `docs/requirements.md` (sección 4, requerimiento 8, y sección 6 de
  suposiciones sobre metadatos de IdP)
- `.ai/tasks/_closed/GestionSolicitudes/handoff.md` (riesgo de falta de
  control de acceso que esta tarea empieza a cerrar)

## Contexto mínimo sugerido
Tamaño **medium**: revisar los 4 archivos de `redi` listados arriba como
referencia directa, más `app/Models/User.php` de este proyecto (para
adaptar el aprovisionamiento a su esquema, sin tabla `roles`).
