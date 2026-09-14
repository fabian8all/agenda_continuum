# Plan de ejecución: MigrarBootstrap

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Quitar `tailwindcss` (paquete, `tailwind.config.js`,
      directiva en `resources/css/app.css`); instalar `bootstrap` + `sass`.
- [x] Paso 2 — `resources/scss/app.scss` (import de Bootstrap),
      `vite.config.js` y `postcss.config.js` actualizados.
- [x] Paso 3 — Reescribir con clases de Bootstrap: `layouts/app.blade.php`,
      `home.blade.php`, `livewire/reservation-form.blade.php`,
      `livewire/request-manager.blade.php`, `livewire/scenario-catalog.blade.php`,
      `livewire/calendar.blade.php`. Eliminar `welcome.blade.php` (dead
      code, Tailwind del scaffold original).
- [x] Paso 4 — Verificar build (`npm run build`), suite completa de tests,
      y las vistas reales en el navegador (con y sin sesión).

## Estado actual
Los 4 pasos están completos. `npm run build` compila sin error (233 KB de
CSS, incluye Bootstrap confirmado por `grep .btn-primary` en el archivo
servido). Suite completa: 32/32 tests, sin regresiones (ningún test
dependía de clases CSS). Verificado con `curl` que `/`, `/escenarios`,
`/calendario`, `/solicitudes/nueva` y `/solicitudes` usan clases Bootstrap
(`form-control`, `form-select`, `btn btn-primary`, `btn-success`, etc.) y
que no queda ninguna clase de Tailwind en `resources/views/` (`grep`
verificado). `resumen.md` y `docs/backlog.md` actualizados (ya no
mencionan Tailwind salvo `docs/requirements.md`, que documenta
correctamente "sin Tailwind" desde el inicio).

**Tarea completa.** Falta commitear y cerrarla con `continuum task close`.
