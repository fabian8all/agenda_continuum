# Handoff: ConfiguracionCI

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Correr automáticamente la suite de PHPUnit en cada PR/push (ver `task.md`).

## Archivos revisados
- `.ai/state/topics/resumen.md` ("Configurar CI" en próximos pasos)
- `.github/workflows/continuum-doctor.yml` (convención GitHub Actions ya
  establecida en este repo, por el scaffold de Continuum)
- `phpunit.xml`, `.env.example`

## Archivos modificados/creados
- `.github/workflows/tests.yml` (nuevo) — job `phpunit`: servicio
  `mysql:8.4` (`MYSQL_DATABASE: testing`), PHP 8.2 (`shivammathur/setup-php`),
  cache de Composer, `composer install`, `.env` desde `.env.example` +
  `key:generate`, Node 22 (`cache: npm`), `npm ci`, `npm run build`,
  `php artisan test`.
- `.github/workflows/continuum-doctor.yml` — se agregó `master` a
  `push.branches` (solo tenía `main`).

## Decisión(es) tomada(s)
- **Sin la suite de accesibilidad en CI**: confirmado explícitamente con
  el usuario antes de escribir el workflow (Playwright/Chromium añade
  descarga pesada y más puntos de falla; queda como comando manual
  documentado en `tests/accessibility/README.md`).
- **Las variables `DB_HOST`/`DB_USERNAME`/`DB_PASSWORD` se fijan como
  `env:` del job**, no se tocan `.env.example` ni `phpunit.xml`: Laravel
  usa `Dotenv::createImmutable()`, que no sobreescribe variables de
  entorno ya presentes en el proceso — así el mismo `.env.example`
  (`DB_HOST=mysql`, para la red interna de Sail) sigue funcionando en
  local sin que la CI necesite un valor distinto en el propio archivo.
  `DB_DATABASE=testing` ya lo fuerza `phpunit.xml` en ambos casos.
- **`npm run build` es un paso obligatorio antes de las pruebas**: las
  vistas usan `@vite(...)`; sin el manifest compilado, cualquier prueba
  Feature que renderice una vista completa lanza
  `ViteManifestNotFoundException`. Esto no era visible en Sail porque el
  `public/build/` local ya existía de sesiones anteriores.
- **Se usa el usuario `root` de MySQL en CI** (no `sail`/`password` como
  en `.env.example`): es la forma más simple de garantizar permisos sin
  un paso adicional de `GRANT`; no hay necesidad de imitar exactamente
  las credenciales de Sail en un servicio efímero de CI.
- **Se corrigió `continuum-doctor.yml`** de paso: su trigger de push solo
  escuchaba `main`; con la rama local `master` (y sin remoto propio en
  GitHub todavía) ese workflow nunca se habría disparado por push
  directo. Es una corrección de una línea, directamente relacionada con
  "que la CI de este repo realmente funcione", no scope creep.

## Suposiciones vigentes
- Se asume que ejecutar en `ubuntu-latest` con un servicio `mysql:8.4`
  standalone (no en un contenedor con red compartida) es suficiente;
  no se necesitó `container:` para el job porque los servicios de GitHub
  Actions son alcanzables por `127.0.0.1` con el puerto publicado cuando
  el job corre directo en el runner.

## Validación
- Ejecutada localmente (no fue posible correr el workflow de GitHub
  Actions real — ver "Riesgos"): `sail npm ci` desde `package-lock.json`
  sin errores · `sail npm run build` tras `npm ci` · simulación de un
  `.env` generado desde cero (`cp .env.example .env` + `key:generate`,
  respaldando y restaurando después el `.env` real de este entorno) ·
  `sail artisan test` con ese `.env` fresco: 59/59 passed, sin
  regresiones · `python3 -c "import yaml; yaml.safe_load(...)"` confirma
  que el YAML del workflow es sintácticamente válido.
- No ejecutada / pendiente: correr el workflow de verdad en GitHub
  Actions — requiere que este repositorio tenga un remoto propio en
  GitHub, que todavía no existe.

## Riesgos / dudas abiertas
- **No hay validación end-to-end real de este workflow.** `git remote -v`
  solo muestra el remoto `continuum` (ajeno, del scaffold de la
  herramienta Continuum) — este proyecto no se ha subido a GitHub
  todavía. La simulación local cubre la parte de Laravel/Node/MySQL, pero
  no puede probar detalles específicos de GitHub Actions (el
  comportamiento exacto de `services:`, `actions/cache`, permisos del
  runner). Recomendado: hacer un push de prueba a un repo de GitHub y
  revisar la primera corrida antes de confiar en el gate para PRs reales.

## Siguiente paso recomendado
Crear el remoto de GitHub para este proyecto y hacer el primer push, para
poder validar este workflow (y `continuum-doctor.yml`) corriendo de
verdad. Después de eso, lo que queda en `resumen.md` es: registrar el
`entityId` con el IdP real de DGRE, notificaciones por correo al
solicitante, y definir sprints de 2 semanas.
