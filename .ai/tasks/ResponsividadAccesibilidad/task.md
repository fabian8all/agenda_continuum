# Tarea: ResponsividadAccesibilidad

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Que la UI existente sea responsiva en móvil/escritorio y cumpla WCAG 2.1
AA (requerimientos no funcionales de `docs/requirements.md`, épica 9 de
`docs/backlog.md`), y dejar infraestructura de auditoría automatizada
(axe-core) para verificarlo en el futuro — decisión explícita del usuario
de incluir esa infraestructura en esta tarea.

## Incluido en el alcance
- Corregir el bug real de responsividad: el navbar usaba
  `navbar-expand-md` sin el botón de colapso (`navbar-toggler` +
  `.collapse.navbar-collapse`), así que en móvil los enlaces no se
  ocultaban detrás de un menú — simplemente se amontonaban. Se agrega el
  botón, el JS de Bootstrap (`Collapse`) necesario, y se ajustan las filas
  de tarjetas (`RequestManager`, `AuditLogViewer`) para apilarse en
  pantallas angostas en vez de desbordar.
- Pase manual de accesibilidad sobre las vistas existentes: skip link,
  `aria-current="page"` en el enlace activo de nav, `role="status"` /
  `role="alert"` en los mensajes dinámicos (Livewire no recarga la
  página — WCAG 4.1.3 Status Messages), `aria-describedby` ligando cada
  error de formulario a su campo, `scope="col"` en encabezados de tabla,
  listas semánticas (`ul`/`li`) en catálogo y calendario, texto oculto
  (`visually-hidden`) para desambiguar botones repetidos ("Editar",
  "Aprobar", etc.) para lectores de pantalla.
- Instalar Playwright + `@axe-core/playwright` como infraestructura de
  pruebas automatizadas de accesibilidad (`tests/accessibility/`), separada
  de la suite de PHPUnit. Corre contra la app real en Sail.
- Corregir cualquier violación real que la auditoría automatizada
  encuentre — encontró y se corrigió un problema real de contraste:
  `$secondary` por defecto de Bootstrap (`#6c757d`) no cumplía 4.5:1 sobre
  los fondos claros de esta app.

## Explícitamente fuera de alcance
- Instalar Playwright/axe como parte de `sail artisan test` o de algún
  pipeline de CI: no existe CI todavía en este proyecto (sigue pendiente
  en `resumen.md`); estas pruebas se documentan como un comando manual
  aparte (`npm run test:a11y`).
- Auditoría de accesibilidad de contenido dinámico ya cargado con datos
  reales de producción, o pruebas con lectores de pantalla reales (NVDA/
  VoiceOver): el alcance es lo que `axe-core` puede verificar
  automáticamente más una revisión manual razonada, no una auditoría de
  accesibilidad profesional completa.
- Tocar el Dockerfile de Sail para dejar Chromium instalado de forma
  persistente: los navegadores de Playwright y sus dependencias de
  sistema se instalaron en el contenedor en ejecución, pero se pierden si
  el contenedor se reconstruye (`sail build --no-cache`) — documentado en
  el README de la carpeta de pruebas, no se pidió resolverlo de forma
  permanente.

## Write-set (archivos que se espera tocar)
- `resources/views/layouts/app.blade.php` (navbar colapsable, skip link,
  aria-current)
- `resources/js/app.js` (import de `bootstrap/js/dist/collapse`)
- `resources/scss/app.scss` (override de `$secondary` por contraste)
- `resources/views/livewire/reservation-form.blade.php`,
  `user-manager.blade.php`, `request-manager.blade.php`,
  `audit-log-viewer.blade.php`, `scenario-catalog.blade.php`,
  `calendar.blade.php` (aria-describedby, roles de alerta, listas
  semánticas, scope de tabla, texto oculto)
- `package.json` (nuevas devDependencies + script `test:a11y`)
- `playwright.config.js`, `tests/accessibility/` (nuevos)
- `.gitignore` (artefactos de Playwright)

## Fuentes de verdad a leer antes de empezar
- `docs/requirements.md` (no funcionales: responsividad, accesibilidad)
- `docs/backlog.md` (épica 9, tareas técnicas de responsividad/accesibilidad)
- Todas las vistas Blade existentes (son el objeto de esta auditoría)

## Contexto mínimo sugerido
Tamaño **medium**: recorrer las vistas existentes (ya conocidas de tareas
anteriores) más la nueva infraestructura de pruebas, sin explorar el resto
del código.
