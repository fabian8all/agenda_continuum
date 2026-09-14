# Plan de ejecución: ConfiguracionCI

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Confirmar con el usuario el alcance (sin la suite de
      accesibilidad en CI).
- [x] Paso 2 — Escribir `.github/workflows/tests.yml`: servicio MySQL,
      PHP 8.2, Node 22, build de assets, `php artisan test`.
- [x] Paso 3 — Validar localmente lo que la CI hará en un checkout
      limpio: `npm ci` desde el lockfile, y un `.env` generado desde
      `.env.example` + `key:generate` (sin tocar el `.env` real de este
      entorno) corriendo la suite completa.
- [x] Paso 4 — Corregir `continuum-doctor.yml` (trigger de push solo
      escuchaba `main`, no `master`).

## Estado actual
Los 4 pasos están completos. Validado localmente: `npm ci` + `npm run
build` desde el lockfile sin errores; un `.env` generado desde
`.env.example` (respaldando y restaurando el `.env` real de este entorno
después) corrió la suite completa con éxito (59/59). No fue posible
correr el workflow de GitHub Actions de verdad porque este repositorio no
tiene todavía un remoto propio en GitHub (`git remote -v` solo muestra el
remoto `continuum`, ajeno a este proyecto) — la validación end-to-end
real queda pendiente del primer push a un repositorio de GitHub.

**Tarea completa (con la salvedad de arriba).** Falta commitear y
cerrarla con `continuum task close`.
