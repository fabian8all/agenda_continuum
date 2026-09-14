# Backlog – Agenda de Escenarios Educativos

## Épicas
1. **Catálogo de Escenarios** – Visualizar y buscar escenarios disponibles.
2. **Calendario de Eventos** – Mostrar agenda filtrable por escenario.
3. **Solicitud de Uso** – Permitir a los profesores solicitar aulas/laboratorios.
4. **Gestión de Solicitudes** – Aprobar, rechazar, marcar como terminado o cancelado.
5. **Administración de Usuarios y Roles** – CRUD de usuarios y asignación de roles.
6. **Acceso Público** – Visualización pública del catálogo y calendario.
7. **Auditoría y Registro** – Historizar acciones críticas.
8. **Autenticación Federada** – Integración con SimpleSAML.
9. **Responsividad y Accesibilidad** – UI adaptable a móvil/desktop y cumplimiento WCAG 2.1 AA.

## Historias de Usuario (MVP)
### 1. Catálogo de Escenarios
- **Como** visitante o solicitante, **quiero** ver una lista de escenarios con nombre, capacidad y recursos, **para** seleccionar el que necesito.
- **Criterios de aceptación**:
  - Lista paginada y filtrable.
  - Cada item muestra nombre, capacidad, recursos, administrador responsable.
  - Enlaces a detalle del escenario.

### 2. Calendario de Eventos
- **Como** visitante o solicitante, **quiero** ver un calendario que muestre los eventos próximos, **para** conocer la disponibilidad.
- **Criterios de aceptación**:
  - Vista tipo calendario mensual.
  - Filtro por escenario.
  - Estado del evento coloreado (pendiente, aprobado, terminado, cancelado).

### 3. Formulario de Solicitud
- **Como** profesor, **quiero** llenar un formulario indicando escenario, fecha/hora y descripción, **para** reservar el espacio.
- **Criterios de aceptación**:
  - Validación de disponibilidad (sin solapamientos).
  - Mensaje de confirmación al crear solicitud.
  - La solicitud se crea con estado **pendiente**.

### 4. Gestión de Solicitudes (Administrador de Escenario)
- **Como** administrador de escenario, **quiero** revisar las solicitudes asignadas a mis escenarios, **para** aprobar o rechazar.
- **Criterios de aceptación**:
  - Lista de solicitudes pendientes con botón **Aprobar** y **Rechazar**.
  - Notificación al solicitante vía email.
  - Cambiar estado a **aprobado** o **rechazado**.

### 5. Cierre de Evento
- **Como** administrador de escenario, **quiero** marcar un evento como **terminado** o **cancelado**, **para** reflejar su resultado real.
- **Criterios de aceptación**:
  - Acción disponible solo para eventos con estado **aprobado** y cuya fecha/hora ya pasó.
  - Registro de cambio de estado.

### 6. Administración de Usuarios (Administrador General)
- **Como** administrador general, **quiero** crear, editar y eliminar usuarios y asignarles roles, **para** gestionar el acceso.
- **Criterios de aceptación**:
  - CRUD completo de usuarios.
  - Asignación de uno o varios escenarios a administradores de escenario.
  - Integración con SimpleSAML para autenticación.

### 7. Acceso Público
- **Como** visitante, **quiero** ver catálogo y calendario sin iniciar sesión, **para** conocer la oferta.
- **Criterios de aceptación**:
  - Solo lectura de catálogo y calendario.
  - No muestra botones de creación/edición.

### 8. Auditoría
- **Como** administrador general, **quiero** consultar un log de acciones (creación, aprobación, rechazo, cierre), **para** auditoría y trazabilidad.
- **Criterios de aceptación**:
  - Tabla filtrable por tipo de acción y rango de fechas.
  - Exportable a CSV.

## Tareas Técnicas (por épica)
- **Catálogo**: Modelo `Scenario`, migración, API REST, vista Vue, pruebas unitarias.
- **Calendario**: Integrar FullCalendar, endpoint de eventos, filtro por escenario, pruebas de integración.
- **Formulario**: Componente Vue con picker de fecha/hora, validación de colisiones, controlador Laravel, notificación.
- **Gestión**: Dashboard admin, acciones approve/reject, envío de correo (Laravel Mail), pruebas E2E.
- **Cierre**: Botón estado, lógica de negocio, registro de auditoría.
- **Usuarios**: Modelo `User` con roles (spatie/laravel-permission), integración SimpleSAMLphp, UI admin.
- **Público**: Rutas públicas, middleware de solo‑lectura.
- **Auditoría**: Tabla `audit_logs`, listener de eventos, UI de consulta.
- **Responsividad**: Utilizar el grid y las utilidades de Bootstrap, pruebas en dispositivos.
- **Accesibilidad**: Auditar con axe, añadir atributos ARIA, colores contrastados.

## Prioridad (MVP → Release)
1. **Escenarios, Calendario y Solicitud** (fundamental).
2. **Gestión de Solicitudes**.
3. **Autenticación Federada**.
4. **Administración de Usuarios**.
5. **Acceso Público**.
6. **Auditoría**.
7. **Responsividad y Accesibilidad** (iterativo).

## Próximos pasos
- Aprobar este backlog.
- Estimar effort (puntos) para cada historia.
- Definir sprints (2‑semana) e iniciar desarrollo.

---
*Backlog generado automáticamente a partir del documento de requerimientos.*
