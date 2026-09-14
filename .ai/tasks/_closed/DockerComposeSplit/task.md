# Tarea: DockerComposeSplit

**Creada:** 2026-09-14 · **Tamaño:** small · **Owner:** (sin asignar) · **Rol:** Desarrollo de funcionalidad (`comun/gestion-proyecto`)

## Objetivo
Restaurar `docker-compose.yml` + `docker-compose.override.yml` para el
ambiente de desarrollo local, corrigiendo un error de una sesión anterior
que los borró creyéndolos duplicados exactos de `compose.yaml` (ver
`.ai/state/archive/handoffs/2026-09-11T230125Z.md`, punto 3). El patrón
correcto —confirmado en `../agenda3/agenda3-app`, que sí los tiene
commiteados— es: `docker-compose.yml` es la base generada por Sail sin
tocar, y `docker-compose.override.yml` agrega solo la personalización de
red del proxy local (`VIRTUAL_HOST`/`VIRTUAL_PORT`/red `proxy` externa),
que Docker Compose fusiona automáticamente con la base.

## Incluido en el alcance
- `docker-compose.yml`: contenido base de Sail (servicios `laravel.test` +
  `mysql`, red `sail`, volumen `sail-mysql`), tal como estaba antes de que
  se le fusionara la personalización del proxy.
- `docker-compose.override.yml`: solo `VIRTUAL_HOST`/`VIRTUAL_PORT` y la
  red externa `proxy` para `laravel.test` (lo que hoy vive mezclado dentro
  de `compose.yaml`).
- Eliminar `compose.yaml`: tener `compose.yaml` y `docker-compose.yml` al
  mismo tiempo es la ambigüedad que Sail ya avisaba en la sesión anterior
  (ambos son nombres que Docker Compose reconoce por defecto).
- Verificar que `sail down && sail up -d` levanta el mismo ambiente
  (mismos puertos, misma red proxy, mismo host `agenda3_c.net`).

## Explícitamente fuera de alcance
- Cambiar versión de PHP/MySQL o cualquier otro valor del servicio: se
  preserva exactamente lo que ya estaba corriendo.
- Migrar cualquier otra configuración de `../agenda3/agenda3-app` (como su
  `custom.ini` de PHP): este proyecto no lo tenía y no se pidió agregarlo.

## Write-set (archivos que se espera tocar)
- `docker-compose.yml` (nuevo)
- `docker-compose.override.yml` (nuevo)
- `compose.yaml` (eliminar)

## Fuentes de verdad a leer antes de empezar
- `../agenda3/agenda3-app/docker-compose.yml`,
  `docker-compose.override.yml` (patrón de referencia)
- `compose.yaml` actual de este proyecto (contenido a repartir entre los
  dos archivos nuevos)
- `.ai/state/archive/handoffs/2026-09-11T230125Z.md` (contexto del error
  original)

## Contexto mínimo sugerido
Tamaño **small**: 3 archivos concretos, sin explorar carpetas completas.
