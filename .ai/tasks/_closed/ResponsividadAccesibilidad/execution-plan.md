# Plan de ejecución: ResponsividadAccesibilidad

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Corregir el navbar (botón de colapso + JS de Bootstrap) y
      ajustar filas flex a `flex-column`/`flex-sm-row` donde podían
      desbordar en móvil.
- [x] Paso 2 — Pase manual de accesibilidad: skip link, `aria-current`,
      `role="status"`/`role="alert"`, `aria-describedby`, `scope="col"`,
      listas semánticas, texto oculto para botones ambiguos.
- [x] Paso 3 — Instalar Playwright + `@axe-core/playwright`, escribir
      `tests/accessibility/pages.spec.js` (7 páginas + 2 pruebas de
      navbar responsivo) y su `global-setup.js`.
- [x] Paso 4 — Correr la auditoría, corregir violaciones reales
      encontradas (contraste de `$secondary`), volver a correr hasta 0
      violaciones.

## Estado actual
Los 4 pasos están completos. `npm run test:a11y`: 9/9 passed (0
violaciones WCAG 2.1 A/AA en las 7 páginas auditadas). `sail artisan
test`: 59/59, sin regresiones. Verificado con Playwright que el navbar se
colapsa correctamente en viewport móvil (375px) y muestra el menú
completo en escritorio (1280px).

**Tarea completa.** Falta commitear y cerrarla con `continuum task close`.
