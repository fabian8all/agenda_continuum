# Notas de trabajo: modelo-espacios-reservas

Bitácora libre mientras se trabaja la tarea. No es para lectores externos:
es memoria operativa de la propia sesión (hipótesis activas, callejones sin
salida, cosas por confirmar). Si algo aquí termina siendo una decisión firme,
pasa a `handoff.md` o a un ADR — este archivo se archiva junto con la tarea.

## Hipótesis activas
-

## Evidencia recopilada
- No hay `php` en el host; validar con `./vendor/bin/sail` (imagen `sail-8.2/app`
  ya existía, arriba en ~1 min). Puerto/servicios libres: APP_PORT=8090,
  VITE_PORT=5177, FORWARD_DB_PORT=3317 (no chocan con otros proyectos sail
  corriendo en esta máquina).
- `sail artisan test` completo: 8/8 passed. `tools/continuum doctor`: 0
  problemas, 0 advertencias.

## Callejones sin salida (para no repetirlos)
- `compose.yaml` y `docker-compose.yml` coexisten idénticos en la raíz;
  Sail imprime un warning de ambigüedad en cada comando (usa `compose.yaml`
  por orden alfabético/precedencia). No se resolvió aquí — está fuera del
  write-set de esta tarea.
