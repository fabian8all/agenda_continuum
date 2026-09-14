# Tarea: DisenoVisual

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Pulir el look general de la app con una identidad institucional propia
(paleta de color, tipografía) en vez del Bootstrap genérico actual, y
mejorar la presentación visual del catálogo y el inicio — sin tocar la
lógica de ninguna página (decisión explícita del usuario).

## Incluido en el alcance
- Paleta: `$primary` de Bootstrap pasa del azul por defecto a
  `#4c8300` (verde), el mismo `--main-color` institucional que usa
  `../redi/redi-app` (otro proyecto de DGRE) en su CSS — reutilizar un
  valor de color ya en uso en el ecosistema DGRE, no inventar uno nuevo.
  `$secondary` se mantiene como quedó en `ResponsividadAccesibilidad`
  (`#495057`, ya validado por contraste).
- Tipografía: familia `Source Sans Pro` (vía Google Fonts) con el mismo
  stack de *fallback* que usa `redi` (`'IBM Plex Sans', Helvetica,
  'Segoe UI', Arial`), en vez de la tipografía por defecto del sistema
  que trae Bootstrap sin personalizar.
- Navbar: acento de color institucional (borde inferior, enlace activo en
  el color primario) en vez del gris plano actual.
- Catálogo de Escenarios: de lista (`list-group`) a cuadrícula de
  tarjetas (`card`) con sombra sutil al pasar el cursor — más acorde a un
  "catálogo" visual.
- Inicio: refinar espaciado/tipografía del texto introductorio y el
  efecto hover de las tarjetas existentes.
- Volver a correr la auditoría de accesibilidad (`npm run test:a11y`)
  después de cambiar colores, porque un color institucional mal elegido
  puede fallar contraste igual que pasó con el `$secondary` de Bootstrap
  en la tarea anterior.

## Explícitamente fuera de alcance (confirmado con el usuario)
- Reemplazar la lista de texto del calendario por un widget visual tipo
  FullCalendar: se preguntó explícitamente y el usuario eligió el
  alcance de diseño visual, no esta opción.
- Tocar la lógica de ningún componente Livewire/controlador: solo Blade
  (markup/clases), Sass y la carga de la fuente. Ningún archivo `.php` de
  `app/` debería tocarse en esta tarea.
- Usar el logo de `../redi/redi-app` (`logo.png`/`logo_small.png`): es la
  marca del producto REDI específicamente (un repositorio educativo, logo
  morado), no el logo institucional genérico de la UCOL — usarlo aquí
  representaría a esta app como si fuera REDI. Tampoco se usa
  `logo_ucol_white.svg` (variante monocromática pensada para fondos
  oscuros/de color) sin más contexto sobre su uso autorizado fuera de
  `redi`. Solo se reutiliza el **valor de color**, no ninguna imagen.
- Agregar un pie de página institucional: no se pidió y cambia la
  estructura de la página más allá de "catálogo/inicio".

## Write-set (archivos que se espera tocar)
- `resources/scss/app.scss` (paleta, tipografía)
- `resources/views/layouts/app.blade.php` (fuente de Google Fonts, acento
  de navbar)
- `resources/views/home.blade.php` (espaciado, hover de tarjetas)
- `resources/views/livewire/scenario-catalog.blade.php` (lista → tarjetas)

## Fuentes de verdad a leer antes de empezar
- `../redi/redi-app/resources/css/style.css` (paleta y tipografía de
  referencia ya en uso en DGRE)
- `.ai/tasks/_closed/ResponsividadAccesibilidad/handoff.md` (por qué
  `$secondary` ya se cambió, y que hay que volver a correr `test:a11y`
  tras tocar colores)

## Contexto mínimo sugerido
Tamaño **medium**: 4 archivos concretos, pero cada cambio de color debe
verificarse contra la auditoría de accesibilidad ya existente.
