# Handoff: MigrarBootstrap

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Quitar Tailwind CSS y migrar las vistas ya construidas a Bootstrap 5 +
Sass, por decisión explícita del usuario, siguiendo el patrón de
`../agenda3/agenda3-app` (ver `task.md`).

## Archivos revisados
- `../agenda3/agenda3-app/package.json`, `vite.config.js`,
  `postcss.config.js`, `resources/scss/app.scss`,
  `resources/views/components/layouts/*.blade.php` (patrón de referencia)
- `docs/requirements.md` (restricción "sin Tailwind" ya documentada desde
  el inicio del proyecto)

## Archivos modificados/creados
- `package.json`/`package-lock.json` — se quitó `tailwindcss`, se agregó
  `bootstrap` y `sass` (dev dependencies, como en `agenda3-app`).
- `tailwind.config.js` (eliminado).
- `postcss.config.js` — solo `autoprefixer` (sin plugin de Tailwind).
- `resources/css/app.css` (eliminado) → `resources/scss/app.scss` (nuevo,
  solo `@import "bootstrap/scss/bootstrap"`, sin overrides de variables ni
  capa de compatibilidad Tailwind).
- `vite.config.js` — entrypoint cambia a `resources/scss/app.scss`; se
  agregó `css.preprocessorOptions.scss` para silenciar deprecations de
  Sass que dispara Bootstrap 5.3 (mismo bloque que `agenda3-app`).
- `resources/views/layouts/app.blade.php` — navbar de Bootstrap
  (`navbar`, `container`) en vez de utilidades Tailwind.
- `resources/views/home.blade.php` — grid (`row`/`col`) y `card`.
- `resources/views/livewire/reservation-form.blade.php` — `form-label`,
  `form-control`/`form-select`, `is-invalid`/`invalid-feedback`, `alert`,
  `btn btn-primary`.
- `resources/views/livewire/request-manager.blade.php` — `alert`,
  `btn-success`/`btn-danger`/`btn-primary`/`btn-secondary`.
- `resources/views/livewire/scenario-catalog.blade.php`,
  `resources/views/livewire/calendar.blade.php` — `list-group` (antes HTML
  plano sin clases).
- `resources/views/welcome.blade.php` (eliminado) — scaffold por defecto
  de Laravel, cargado de Tailwind, sin ninguna ruta que lo usara desde
  hacía varias tareas.
- `.ai/state/topics/resumen.md`, `docs/backlog.md` — dejan de mencionar
  Tailwind.

## Decisión(es) tomada(s)
- **No se replicó la capa extensa de utilidades de compatibilidad
  Tailwind→Bootstrap de `agenda3-app`** (`.text-gray-*`, `.stack-*`,
  `.badge-status`, variables de color/espaciado/sombra calcadas de
  Tailwind, etc.). Esa capa existe ahí porque migran una UI Vue en
  producción que debía verse pixel-idéntica; este proyecto no tenía ese
  compromiso visual previo (las vistas se construyeron y se descartaron
  en la misma sesión), así que se usan clases de Bootstrap estándar
  directamente, sin capa de compatibilidad. **Documentado explícitamente
  como fuera de alcance en `task.md` y confirmado con el usuario antes de
  empezar la tarea.**
- `bootstrap` y `sass` se instalaron como `devDependencies` (no
  `dependencies`), igual que en `agenda3-app`: se compilan a CSS estático
  en build time, no se usan en runtime del servidor.
- No se instaló `@popperjs/core` ni el JS bundle de Bootstrap: las vistas
  actuales no usan componentes interactivos de Bootstrap (modales,
  dropdowns, tooltips) — explícitamente fuera de alcance en `task.md`.
- Se eliminó `resources/views/welcome.blade.php` en vez de dejarlo
  huérfano: sostenerlo habría dejado Tailwind vivo en el repo pese a la
  decisión, sin que sirviera ningún propósito (no hay ruta que lo
  referencie desde la tarea `AutenticacionFederada`, que reemplazó `/`
  por `home.blade.php`).

## Suposiciones vigentes
- Se asume que el estilo visual resultante (Bootstrap "de fábrica", sin
  personalización de variables) es aceptable para esta fase; no se pidió
  ni se hizo ningún ajuste de marca/color institucional. Si se quiere un
  look más cercano al de `agenda3-app` (azules/grises específicos), eso
  implicaría agregar overrides de variables Sass en una tarea aparte.

## Validación
- Ejecutada: `sail npm run build` (compila sin error; `app.css` resultante
  233 KB, contiene `.btn-primary` y otras clases de Bootstrap) · `sail
  artisan test` (32/32 passed, 83 assertions, sin regresiones — ningún
  test dependía de clases CSS) · `grep` sobre `resources/views/` confirma
  cero clases de utilidad Tailwind restantes · verificación manual con
  `curl` de `/`, `/escenarios`, `/calendario`, `/solicitudes/nueva` (con
  sesión simulada) y `/solicitudes` (con sesión), confirmando las clases
  Bootstrap esperadas en cada una.
- No ejecutada / pendiente: revisión visual humana en un navegador real
  (esta sesión solo verificó markup/CSS servido vía `curl`, no capturó
  pantallas); accesibilidad WCAG 2.1 AA (prioridad 7, fuera de alcance).

## Riesgos / dudas abiertas
- Ninguno nuevo. El resultado visual es "Bootstrap de fábrica" sin marca
  institucional — aceptable para esta fase según lo conversado, pero
  alguien debería revisarlo en un navegador real antes de considerarlo
  definitivo.

## Siguiente paso recomendado
Administración de Usuarios y Roles (prioridad 4 en `resumen.md`) sigue
siendo el siguiente elemento de la prioridad MVP, ahora sin deuda de
Tailwind ni de infraestructura Docker pendiente.
