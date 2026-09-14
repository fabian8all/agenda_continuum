# Plan de ejecución: DisenoVisual

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Paleta: `$primary` verde institucional (`#4c8300`, mismo
      que `../redi/redi-app`); tipografía `Source Sans 3` vía Google
      Fonts con el mismo stack de fallback que `redi`.
- [x] Paso 2 — Navbar: acento de color (borde inferior, enlace activo/hover
      en verde vía las CSS custom properties de Bootstrap 5.3), brand en
      negrita y color primario.
- [x] Paso 3 — Inicio: hero con más aire/jerarquía tipográfica; tarjetas
      con elevación sutil al pasar el cursor (`.home-card`).
- [x] Paso 4 — Catálogo de Escenarios: de lista a cuadrícula de tarjetas
      (`ul.row.list-unstyled` + `li.col` + `.card`, conservando la
      semántica de lista), recursos como badges.
- [x] Paso 5 — Volver a correr `npm run test:a11y` tras el cambio de
      color (el verde institucional se verificó ≈4.6:1 de contraste con
      texto blanco, pasa AA) y `sail artisan test`.

## Estado actual
Los 5 pasos están completos. `npm run test:a11y`: 9/9 passed, 0
violaciones tras el cambio de paleta. `sail artisan test`: 59/59, sin
regresiones (no se tocó ningún archivo `.php` de `app/`). Revisado
visualmente con capturas de Playwright (`/` y `/escenarios`): color
institucional, navbar con acento, tarjetas con badges de recursos.

**Tarea completa.** Falta commitear y cerrarla con `continuum task close`.
