# Tarea: ConfiguracionCI

**Creada:** 2026-09-14 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Correr automáticamente la suite de pruebas de PHPUnit en cada PR y push,
uno de los "Próximos pasos" pendientes en `.ai/state/topics/resumen.md`.

## Incluido en el alcance
- `.github/workflows/tests.yml` (nuevo): job `phpunit` en GitHub Actions
  (ya es el proveedor establecido — el proyecto ya tenía
  `.github/workflows/continuum-doctor.yml`), con servicio MySQL 8.4,
  instala PHP 8.2 + Composer, Node 22 + npm, compila los assets
  (necesario porque las vistas usan `@vite(...)`), y corre
  `php artisan test`.
- Corregir `.github/workflows/continuum-doctor.yml`: su trigger de push
  solo escuchaba `main`, pero la rama local de este repositorio es
  `master` y todavía no tiene remoto propio en GitHub — el workflow
  nunca se habría disparado por push. Se agregó `master` a la lista.

## Explícitamente fuera de alcance (confirmado con el usuario)
- Correr `tests/accessibility/` (Playwright + axe-core) en CI: requiere
  descargar Chromium y levantar el servidor de la app en cada corrida,
  con más puntos de falla; se decidió dejarlo como comando manual aparte
  (`npm run test:a11y`), tal como ya quedó documentado en
  `.ai/tasks/_closed/ResponsividadAccesibilidad/handoff.md`.
- Métricas de cobertura, análisis estático (PHPStan/Larastan), linter de
  estilo (Pint): no se pidieron y no hay convención previa en el proyecto.
- Despliegue automático (CD): fuera de alcance, no se pidió.

## Write-set (archivos que se espera tocar)
- `.github/workflows/tests.yml` (nuevo)
- `.github/workflows/continuum-doctor.yml` (corrección de rama)

## Fuentes de verdad a leer antes de empezar
- `.ai/state/topics/resumen.md` ("Configurar CI" en Próximos pasos)
- `.github/workflows/continuum-doctor.yml` (convención ya establecida de
  GitHub Actions en este repo)
- `phpunit.xml`, `.env.example` (qué variables ya están aisladas para
  pruebas y cuáles hay que proveer en CI)

## Contexto mínimo sugerido
Tamaño **medium**: 2 archivos concretos, pero requiere validar contra el
comportamiento real de `@vite`, `RefreshDatabase` y las variables de
entorno — no es una tarea trivial de copiar una plantilla.
