# Handoff: Backlog

**Fecha:** 2026-09-14 · **Rol:** Gestión de proyecto (ágil/Scrum) (`comun/gestion-proyecto`)

## Objetivo
Documentar el backlog de producto y los requerimientos del sistema
(`docs/backlog.md`, `docs/requirements.md`) y reflejar lo esencial en
`.ai/state/topics/resumen.md`, antes de empezar a implementar cualquier
épica (ver `task.md`).

## Archivos modificados
- `docs/backlog.md` (nuevo) — épicas y historias de usuario del MVP.
- `docs/requirements.md` (nuevo) — requerimientos funcionales/no
  funcionales, actores, restricciones tecnológicas y suposiciones.
- `.ai/state/topics/resumen.md` — se agregaron las secciones
  "Requerimientos clave" y "Prioridad (MVP → Release)".

## Decisión(es) tomada(s)
- El orden de prioridad del MVP (Escenarios/Calendario/Solicitud primero,
  Gestión de Solicitudes después, luego Autenticación Federada,
  Administración de Usuarios, Acceso Público y Auditoría) quedó fijado en
  `resumen.md` y fue lo que determinó que la siguiente tarea trabajada
  fuera `SolicitudUso`.

## Suposiciones vigentes
- Ninguna épica se implementó en esta tarea; eso quedó explícitamente fuera
  de alcance para las tareas siguientes.

## Validación
- No aplica ejecución de código (tarea de documentación). Los documentos se
  redactaron a partir de los requerimientos institucionales conocidos por
  el usuario.

## Riesgos / dudas abiertas
- Esta tarea se trabajó y se dio por completa antes de que existiera el
  hábito de cerrarla formalmente con `continuum task close`; `task.md` y
  `execution-plan.md` quedaron como plantillas vacías hasta que se
  completaron retroactivamente al momento de este cierre (sesión
  siguiente, la misma que trabajó y cerró `SolicitudUso`).
- Los archivos de esta tarea no se commitearon en el momento en que se
  crearon, sino más adelante, junto con trabajo de otras tareas:
  `docs/backlog.md` y `docs/requirements.md` quedaron en el commit
  `55e5c0d` (feature de `SolicitudUso`, que los usaba como fuente de
  verdad); las secciones nuevas de `resumen.md` quedaron en el commit
  `0f991c9` (higiene de pendientes sueltos de la sesión de `SolicitudUso`).

## Siguiente paso recomendado
Ya ejecutado: la siguiente tarea trabajada fue `SolicitudUso` (ver
`.ai/tasks/_closed/SolicitudUso/`), siguiendo la prioridad 1 del MVP
definida aquí. El siguiente pendiente natural sigue siendo la prioridad 2:
"Gestión de Solicitudes".
