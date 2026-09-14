# Handoff: AutenticacionFederada

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Implementar el login federado (SimpleSAML), replicando el patrón ya usado
en `../redi/redi-app` (paquete `aacotroneo/laravel-saml2`, middleware con
modo simulador vía `.env`), y usarlo para proteger las vistas de acción
que hoy eran públicas (ver `task.md`).

## Archivos revisados
- `../redi/redi-app/app/Http/Middleware/VerifyAuthSaml.php`,
  `AuthController.php`, `config/saml2_settings.php`,
  `config/saml2/test_idp_settings.php`, `.env.example`, `Kernel.php`
  (patrón de referencia)
- `app/Models/User.php` de este proyecto (esquema sin tabla `roles`)

## Archivos modificados/creados
- `composer.json`/`composer.lock` — nueva dependencia
  `aacotroneo/laravel-saml2` (arrastra `onelogin/php-saml` y
  `robrichards/xmlseclibs`).
- `config/saml.php` (nuevo, específico de este proyecto) — lee
  `SAML_SIMULATOR*` vía `config()`.
- `config/saml2_settings.php`, `config/saml2/test_idp_settings.php`
  (nuevos, adaptados de `redi`).
- `app/Http/Middleware/VerifyAuthSaml.php` (nuevo).
- `app/Http/Controllers/Auth/AuthController.php` (nuevo).
- `bootstrap/app.php` — alias `verify.auth` + excepción de CSRF para
  `saml2/*/acs`.
- `routes/web.php` — `/login`, `/logout`; `/solicitudes/nueva` y
  `/solicitudes` ahora requieren `verify.auth`.
- `resources/views/layouts/app.blade.php` — nombre de usuario + cierre de
  sesión, o "Iniciar sesión".
- `.env.example` y `.env` (no versionado) — variables `SAML_*` con
  `SAML_SIMULATOR=true` por defecto en este entorno de desarrollo.
- `tests/Feature/AuthSamlTest.php` (nuevo, 5 tests).

## Decisión(es) tomada(s)
- **`$this->middleware()` en el controlador no existe en Laravel 11**
  (`redi` corre Laravel 8): causaba `Error: Call to undefined method
  App\Http\Controllers\Auth\AuthController::middleware()` al pegarle a
  `/logout`. Se movió `verify.auth` a la definición de la ruta `/login` en
  vez del constructor del controlador.
- **`routesMiddleware` del paquete se dejó en `['web']`**, no en un grupo
  `'saml'` custom como en `redi` (que en Laravel 8, vía `Kernel.php`, era
  de todos modos solo `EncryptCookies` + `AddQueuedCookiesToResponse` +
  `StartSession`, un subconjunto de `web`). Se agregó una excepción de
  CSRF explícita para `saml2/*/acs` en `bootstrap/app.php`, porque el IdP
  hace un POST sin token CSRF a ese endpoint y el grupo `web` de Laravel 11
  sí trae `VerifyCsrfToken`.
- **Aprovisionamiento simplificado respecto a `redi`**: `User::firstOrCreate`
  por email, sin el modelo `Role` ni el flag `CREATE_NEW_USER_RECORD` de
  `redi` (no aplican, este proyecto no tiene tabla `roles` ni la historia
  de "usuario sin permisos" en su `docs/backlog.md`). El usuario nuevo
  recibe el rol por defecto de la columna `role` (`docente`).
- **`x509cert` del IdP real se dejó solo en `env()`, sin valor por defecto
  hardcodeado** (a diferencia de `redi`, que sí trae el certificado de
  prueba de DGRE embebido como default). Es información pública (un
  certificado X.509), pero se prefirió no duplicar ese valor entre dos
  repos — hay que pegarlo en `.env` local si se quiere probar el flujo
  real. **El usuario fue consultado sobre esto antes de cerrar la tarea y
  no pidió cambiarlo.**
- Se protegieron únicamente las dos rutas de "acción" (`/solicitudes/nueva`,
  `/solicitudes`); catálogo, calendario e inicio siguen públicos —
  coincide con la futura épica "Acceso Público" (prioridad 5), que ya da
  por hecho que esas vistas son de solo lectura sin login.

## Suposiciones vigentes
- Se asume que cualquier usuario autenticado (sin importar su `role`)
  puede seguir aprobando/rechazando/cerrando cualquier solicitud en
  `/solicitudes` — la restricción por "administrador del escenario" sigue
  pendiente de Administración de Usuarios/Roles (prioridad 4).
- Se asume que el `entityId` del SP de esta app (`SAML2_TEST_SP_ENTITYID`)
  se registrará con el administrador del IdP de DGRE cuando se quiera
  probar el flujo real; por ahora esa variable queda vacía en
  `.env.example`.

## Validación
- Ejecutada: `sail composer require aacotroneo/laravel-saml2` (sin
  conflictos con Laravel 11) · `sail artisan test` (32/32 passed, 83
  assertions, incluye los 5 tests nuevos de `AuthSamlTest` sin
  regresiones) · flujo completo probado con `curl` y cookies: guest →
  redirect → login simulado → nombre visible en la nav → `/logout` →
  vuelve a "Iniciar sesión"; confirmado que `/`, `/escenarios` y
  `/calendario` responden 200 sin sesión; confirmado con `Livewire::test()`
  + `actingAs()` que `ReservationForm` sigue guardando el `requester_id`
  correcto a través del nuevo flujo de login.
- No ejecutada / pendiente: validación end-to-end del flujo SAML real
  contra `dgre2.ucol.mx` (requiere registrar el `entityId` de esta app del
  lado del IdP, fuera del alcance — ver "Riesgos").

## Riesgos / dudas abiertas
- **El flujo SAML real no está probado.** Está wireado con el mismo
  paquete y patrón que `redi`, pero nadie ha verificado que el IdP de DGRE
  acepte un `entityId` nuevo sin registrarlo antes. Antes de poner
  `SAML_SIMULATOR=false` en cualquier ambiente, alguien con acceso al IdP
  debe confirmar/registrar el SP.
- Sigue sin existir un control de acceso por rol/escenario en
  `RequestManager` — ahora se exige estar logueado, pero no se distingue
  quién puede decidir sobre qué solicitud (ver "Suposiciones").
- El advisory de seguridad de `laravel/framework` que aparece en
  `composer audit` (CRLF injection, path confusion en URLs firmadas) es
  preexistente a esta tarea — afecta la versión de Laravel ya fijada en
  `composer.json` (`^11.31`), no la nueva dependencia SAML. No se tocó por
  no ser parte del alcance.

## Siguiente paso recomendado
Administración de Usuarios y Roles (prioridad 4 en `resumen.md`): con
login funcionando, ya se puede construir la UI de gestión de usuarios y
la asignación de escenarios a administradores, que es lo que finalmente
resolvería la restricción de acceso pendiente en `RequestManager`.
