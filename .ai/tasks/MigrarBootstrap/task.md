# Tarea: MigrarBootstrap

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Quitar Tailwind CSS del proyecto y migrar las vistas ya construidas a
Bootstrap 5 + Sass, por decisión explícita del usuario ("se decidió que no
se usará Tailwind"). `docs/requirements.md` ya documentaba esta
restricción desde el inicio (sección 5: "Frontend con Vue 3 y AlpineJS
(sin Tailwind)"), pero el scaffold inicial y todo el trabajo de UI de esta
sesión (`SolicitudUso`, `GestionSolicitudes`, `AutenticacionFederada`) se
hizo con Tailwind sin que nadie lo notara hasta ahora. Se sigue el patrón
de `../agenda3/agenda3-app` (proyecto hermano más reciente), que usa
Bootstrap 5.3 + Sass.

## Incluido en el alcance
- Quitar `tailwindcss` de `package.json`, `tailwind.config.js`,
  `postcss.config.js` (directiva Tailwind) y `resources/css/app.css`.
- Instalar `bootstrap` + `sass` (misma versión que `agenda3-app`) y crear
  `resources/scss/app.scss` (Bootstrap importado sin capa de
  compatibilidad Tailwind — este proyecto no necesita preservar un look
  Tailwind previo en producción, a diferencia de `agenda3-app`, que
  migraba una UI Vue ya existente).
- Actualizar `vite.config.js` para compilar el nuevo entrypoint Sass.
- Reescribir con clases de Bootstrap las vistas que usaban Tailwind:
  `layouts/app.blade.php`, `home.blade.php`,
  `livewire/reservation-form.blade.php`, `livewire/request-manager.blade.php`.
- Dar un toque de Bootstrap a `livewire/scenario-catalog.blade.php` y
  `livewire/calendar.blade.php` (hoy sin clases, HTML plano) para que la
  UI se vea consistente.
- Eliminar `resources/views/welcome.blade.php`: es el scaffold por
  defecto de Laravel, ya no está enlazado desde ninguna ruta (`/` usa
  `home.blade.php`) y viola la decisión al seguir cargado de Tailwind.

## Explícitamente fuera de alcance
- Replicar la capa extensa de utilidades de compatibilidad Tailwind→Bootstrap
  que tiene `agenda3-app` (`.text-gray-*`, `.stack-*`, `.badge-status`,
  etc.): ese proyecto la necesita porque migra una UI Vue ya en producción
  pixel a pixel; este proyecto no tiene ese compromiso visual previo, así
  que se usan clases de Bootstrap estándar directamente.
- Accesibilidad WCAG 2.1 AA (prioridad 7, iterativa, fuera de alcance de
  esta tarea puntual).
- Cualquier componente/librería JS de Bootstrap (modales, dropdowns): las
  vistas actuales no los necesitan.

## Write-set (archivos que se espera tocar)
- `package.json`, `package-lock.json`
- `tailwind.config.js` (eliminar), `postcss.config.js`
- `resources/css/app.css` (eliminar) → `resources/scss/app.scss` (nuevo)
- `vite.config.js`
- `resources/views/layouts/app.blade.php`
- `resources/views/home.blade.php`
- `resources/views/livewire/reservation-form.blade.php`
- `resources/views/livewire/request-manager.blade.php`
- `resources/views/livewire/scenario-catalog.blade.php`
- `resources/views/livewire/calendar.blade.php`
- `resources/views/welcome.blade.php` (eliminar)

## Fuentes de verdad a leer antes de empezar
- `../agenda3/agenda3-app/package.json`, `vite.config.js`,
  `postcss.config.js`, `resources/scss/app.scss` (patrón de referencia,
  con la salvedad de alcance de arriba)
- `docs/requirements.md` (sección 5, restricción "sin Tailwind")

## Contexto mínimo sugerido
Tamaño **medium**: los ~10 archivos del write-set, sin explorar más allá.
