# Pendientes por prioridad

## Mayores
- Registrar el `entityId` de esta app (`SAML2_TEST_SP_ENTITYID`) con el
  administrador del IdP de DGRE (`dgre2.ucol.mx`), para poder probar el
  flujo SAML real (`SAML_SIMULATOR=false`) — hoy solo funciona el
  simulador. Ver `.ai/tasks/_closed/AutenticacionFederada/handoff.md`.
- Notificaciones por correo al solicitante cuando se aprueba/rechaza/cierra
  su solicitud. El dato que faltaba (`events.requester_id`) ya existe
  desde `.ai/tasks/_closed/SolicitanteEvent/`; falta la lógica de envío
  (Laravel Mail) y las plantillas.

## Menores
- `.ai/tasks/_closed/Backlog/task.md` documenta un gap conocido:
  `spatie/laravel-permission`/tabla `roles` no se adoptó (se usa el enum
  `role` de `users`); revisar si sigue siendo suficiente cuando crezcan
  los roles.
- Configurar protección de rama (`branch protection`) en
  `github.com/fabian8all/agenda_continuum` para `master`, exigiendo los
  checks `tests` y `continuum-doctor` (ya verificados en verde) antes de
  hacer merge.
- Definir sprints de 2 semanas y estimar effort por historia (pendiente
  de gestión de proyecto, no de código).
