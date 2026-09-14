# Plan de ejecución: AutenticacionFederada

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Instalar `aacotroneo/laravel-saml2` y crear
      `config/saml.php`, `config/saml2_settings.php`,
      `config/saml2/test_idp_settings.php` (adaptados de `../redi/redi-app`).
- [x] Paso 2 — `VerifyAuthSaml` + `AuthController` + rutas `/login`,
      `/logout`, alias de middleware en `bootstrap/app.php`.
- [x] Paso 3 — Proteger `/solicitudes/nueva` y `/solicitudes` con
      `verify.auth`; estado de sesión en la navegación.
- [x] Paso 4 — Pruebas de Feature (`AuthSamlTest`, 5 tests) con el
      simulador activo.

## Estado actual
Los 4 pasos están completos. Suite completa: 32/32 tests, 83 assertions,
sin regresiones. Verificado también con `curl` el flujo real de
redirect→login→logout, y que las vistas de solo lectura (`/`,
`/escenarios`, `/calendario`) siguen siendo públicas.

Dos ajustes respecto al plan original de `task.md`, necesarios porque
`redi` corre Laravel 8 y este proyecto Laravel 11:
- `AuthController::__construct()` con `$this->middleware(...)` no existe
  en la clase `Controller` base de Laravel 11 (causaba
  `Error: Call to undefined method ...::middleware()`). Se movió el
  middleware `verify.auth` a la definición de la ruta `/login` en
  `routes/web.php` en vez del constructor del controlador.
- `routesMiddleware` del paquete se dejó en `['web']` en vez de un grupo
  `'saml'` custom (que en `redi`/Laravel 8 solo era un subconjunto de
  `web`); y se agregó una excepción de CSRF en `bootstrap/app.php` para
  `saml2/*/acs` (el IdP hace un POST sin token CSRF a ese endpoint).

**Tarea completa.** Falta commitear y cerrarla con `continuum task close`.
