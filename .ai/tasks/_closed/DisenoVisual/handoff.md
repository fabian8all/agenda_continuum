# Handoff: DisenoVisual

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Pulir el look general de la app con identidad institucional propia
(color, tipografía) y tarjetas más cuidadas en catálogo/inicio, sin tocar
la lógica de ninguna página — alcance confirmado explícitamente con el
usuario entre tres opciones (ver `task.md`).

## Archivos revisados
- `../redi/redi-app/resources/css/style.css` (paleta/tipografía de
  referencia: `--main-color: #4c8300`, stack `'Source Sans Pro','IBM Plex
  Sans',Helvetica,'Segoe UI',Arial`)
- `../redi/redi-app/public/img/logo*.{png,svg}` (revisados y
  descartados — ver decisiones)
- `.ai/tasks/_closed/ResponsividadAccesibilidad/handoff.md`

## Archivos modificados/creados
- `resources/scss/app.scss` — `$primary: #4c8300`; `$font-family-sans-serif`
  = `'Source Sans 3', 'IBM Plex Sans', Helvetica, 'Segoe UI', Arial,
  sans-serif`; override de `--bs-navbar-active-color`/`--bs-navbar-hover-color`;
  clase `.home-card` (elevación al pasar el cursor).
- `resources/views/layouts/app.blade.php` — `<link>` de Google Fonts
  (Source Sans 3); navbar con `border-bottom border-primary border-3`;
  brand en `fw-bold text-primary`.
- `resources/views/home.blade.php` — hero con más jerarquía tipográfica
  (`h2`, `fs-5`); tarjetas con clase `.home-card`.
- `resources/views/livewire/scenario-catalog.blade.php` — de
  `ul.list-group`/`li.list-group-item` a `ul.row.list-unstyled` +
  `li.col-*` + `.card.home-card` (conserva la semántica de lista);
  recursos como `.badge`.

## Decisión(es) tomada(s)
- **Se reutilizó el color `#4c8300` de `redi`, no se copió ningún logo.**
  `redi`'s `logo.png`/`logo_small.png` son la marca del producto REDI
  (repositorio educativo, logo morado con un ícono de caja), no un logo
  institucional genérico de la UCOL — usarlo aquí habría hecho que esta
  app pareciera ser REDI. `logo_ucol_white.svg` es monocromático (pensado
  para fondos oscuros/de color) y no había certeza sobre su uso
  autorizado fuera de ese proyecto. El color sí se reutilizó porque es
  solo un valor de estilo, sin ese riesgo de representación indebida —
  documentado explícitamente como fuera de alcance en `task.md`.
- **"Source Sans Pro" se cargó como "Source Sans 3"**: Google Fonts
  renombró la familia; cargar el nombre viejo no habría traído ninguna
  tipografía real. Se documentó en el propio `app.scss` para que quede
  claro por qué el nombre no coincide textualmente con el de `redi`.
- **El acento de color activo/hover del navbar usa las CSS custom
  properties nativas de Bootstrap 5.3** (`--bs-navbar-active-color`/
  `--bs-navbar-hover-color`) en vez de sobrescribir `.nav-link.active`
  a mano: es el mecanismo que el propio framework expone para esto.
- **Se volvió a correr la auditoría de accesibilidad después de cambiar
  colores**, seteando el precedente correcto que ya había dejado
  `ResponsividadAccesibilidad` (donde un color de Bootstrap sin tocar
  falló contraste): el verde institucional se verificó en ~4.6:1 de
  contraste con texto blanco antes de dar la tarea por completa.

## Suposiciones vigentes
- Se asume que el verde `#4c8300` es aceptable como "el" color
  institucional de esta app aunque no hay una guía de marca oficial
  documentada en este proyecto — se infirió de su uso ya establecido en
  `redi` (otro proyecto de DGRE), no de un documento de identidad visual
  de la UCOL. Si existe una guía de marca oficial distinta, este color
  debería revisarse contra ella.

## Validación
- Ejecutada: `sail npm run build` (compila sin error) · `sail artisan
  test` (59/59 passed, sin regresiones — no se tocó ningún `.php` de
  `app/`) · `sail npm run test:a11y` (9/9 passed, 0 violaciones WCAG 2.1
  A/AA tras el cambio de paleta) · revisión visual con capturas de
  Playwright de `/` y `/escenarios` (archivos temporales, no
  commiteados) · verificación puntual del color computado del enlace
  "Iniciar sesión" (`rgb(73, 80, 87)` = `#495057`, el `$secondary`
  correcto, no azul como parecía a simple vista en la captura).
- No ejecutada / pendiente: revisión por una persona de diseño o
  comunicación institucional de la UCOL/DGRE sobre el uso del color verde
  fuera de `redi` (ver "Suposiciones").

## Riesgos / dudas abiertas
- Ninguno nuevo más allá de la suposición sobre el color institucional
  documentada arriba.

## Siguiente paso recomendado
Con el diseño visual, las 7 épicas del backlog, auditoría, accesibilidad
y CI ya cubiertos, lo que queda en `resumen.md` es: crear el remoto de
GitHub y validar la CI de verdad, registrar el `entityId` con el IdP real
de DGRE, notificaciones por correo al solicitante, y definir sprints.
