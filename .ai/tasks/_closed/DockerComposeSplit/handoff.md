# Handoff: DockerComposeSplit

**Fecha:** 2026-09-14 · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Restaurar `docker-compose.yml` + `docker-compose.override.yml`, corrigiendo
que una sesión anterior los borró creyéndolos duplicados de `compose.yaml`
(ver `task.md`).

## Archivos modificados/creados
- `docker-compose.yml` (nuevo) — base de Sail (`laravel.test` + `mysql`,
  red `sail`, volumen `sail-mysql`), sin la personalización del proxy.
- `docker-compose.override.yml` (nuevo) — `VIRTUAL_HOST`/`VIRTUAL_PORT` y
  la red externa `proxy` para `laravel.test`.
- `compose.yaml` (eliminado) — tenerlo junto a `docker-compose.yml`
  reproducía la ambigüedad que Sail ya avisaba ("Found multiple config
  files with supported names").

## Decisión(es) tomada(s)
- Se repartió el contenido de `compose.yaml` (tal como estaba, con la
  personalización del proxy ya fusionada) entre los dos archivos nuevos,
  en vez de solo copiar `../agenda3/agenda3-app` literalmente — ese
  proyecto corre en un runtime/versión de MySQL distintos (Sail 8.3 vs el
  8.2 de este proyecto, `mysql/mysql-server:8.0` vs `mysql:8.4`) y no
  tenía sentido cambiar eso solo por igualar el patrón de archivos.
- Se verificó con `docker compose config` que la fusión de los dos
  archivos nuevos produce exactamente el mismo resultado que tenía
  `compose.yaml` antes de borrarlo (mismos puertos, misma red `proxy`,
  mismo `VIRTUAL_HOST`), antes de reiniciar los contenedores de verdad.

## Suposiciones vigentes
- Se asume que `docker-compose.override.yml` debe commitearse (no
  gitignorearse): así está en `../agenda3/agenda3-app` (`git ls-files` lo
  confirma tracked ahí), y tiene sentido porque no contiene secretos, solo
  la topología de red del proxy local que usa este entorno de desarrollo.

## Validación
- Ejecutada: `docker compose config` (confirma la fusión antes de tocar
  contenedores) · `sail down && sail up -d` (contenedores recrean sin
  error) · `curl http://localhost:8090/` → 200 · `sail artisan test`
  (32/32 passed, sin regresiones) · `docker inspect` confirma que
  `laravel.test` sigue conectado a las redes `sail` y `proxy`.
- No ejecutada / pendiente: probar `http://agenda3_c.net` desde fuera del
  host (depende de la configuración de DNS/proxy local del usuario, fuera
  del control de esta sesión).

## Riesgos / dudas abiertas
- Ninguno nuevo. El primer `docker compose up` de esta tarea se corrió
  con `docker compose` directo (sin pasar por `./vendor/bin/sail`), lo que
  dejó un archivo `.phpunit.result.cache` con permisos del usuario
  incorrecto dentro del contenedor; se corrigió recreando los contenedores
  con `sail up -d` (que exporta `WWWUSER`/`WWWGROUP` antes de invocar
  `docker compose`). Vale la pena recordar: siempre usar `./vendor/bin/sail`
  en vez de `docker compose` directo en este proyecto.

## Siguiente paso recomendado
Ninguno específico de esta tarea — era una corrección de infraestructura
puntual. Sigue pendiente la migración de Tailwind a Bootstrap 5 + Sass
(tarea separada, en curso en esta misma sesión) y, después de eso,
Administración de Usuarios y Roles según `resumen.md`.
