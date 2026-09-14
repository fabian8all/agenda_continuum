# Documento de Requerimientos – Agenda de Escenarios Educativos

## 1. Introducción
Este documento describe los requerimientos funcionales y no funcionales del proyecto **Agenda de Escenarios Educativos**. El objetivo es permitir a los profesores solicitar el uso de aulas o laboratorios, y que los administradores de cada escenario gestionen y aprueben dichas solicitudes.

## 2. Stakeholders y Roles
| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **Administrador General / Soporte** | Usuario con acceso total al sistema. Puede gestionar usuarios, escenarios y visualizar todos los datos. | CRUD completo sobre usuarios, escenarios, solicitudes y auditoría. |
| **Administrador de Escenario** | Responsable de uno o varios escenarios. Revisa y aprueba/rechaza solicitudes de uso. | Ver y gestionar solicitudes asignadas a sus escenarios; editar información del escenario; ver historial de eventos. |
| **Solicitante (Profesor)** | Usuario que consulta el catálogo, ve el calendario y crea solicitudes de uso. | Ver catálogo y calendario; crear, modificar y cancelar sus propias solicitudes; ver estado de sus solicitudes. |
| **Público General** | Visitante sin autenticación. Sólo visualiza información pública. | Ver catálogo de escenarios y calendario de eventos próximos (solo lectura). |

## 3. Requerimientos Funcionales
1. **Catálogo de Escenarios**
   - Listado de todos los escenarios con datos descriptivos (nombre, capacidad, recursos, administrador responsable). 
2. **Calendario de Eventos**
   - Vista de calendario filtrable por escenario.
   - Indicación del estado del evento (pendiente, aprobado, rechazado, terminado, cancelado).
3. **Formulario de Solicitud**
   - Permite al solicitante seleccionar escenario, fecha/hora y proporcionar descripción.
   - Validación de disponibilidad del escenario.
4. **Gestión de Solicitudes**
   - Administrador de Escenario puede aprobar o rechazar solicitudes.
   - Notificaciones por correo o mediante el portal al solicitante.
   - Cambio de estado a *terminado* o *cancelado* tras el evento.
5. **Administración de Usuarios**
   - Creación, edición y asignación de roles.
   - Integración con federación de identidad (SimpleSAML) para inicio de sesión.
6. **Acceso Público**
   - Visualización del catálogo y del calendario sin necesidad de autenticación.
7. **Auditoría y Registro**
   - Registro de todas las acciones críticas (creación, aprobación, rechazo, finalización, cancelación).

## 4. Requerimientos No Funcionales
- **Responsividad**: Las vistas del solicitante deben renderizarse correctamente en dispositivos móviles y escritorio.
- **Seguridad**: Autenticación federada mediante SimpleSAML de la universidad. Control de acceso basado en roles.
- **Rendimiento**: El calendario debe cargar en menos de 2 s para consultas habituales.
- **Escalabilidad**: Soportar al menos 500 escenarios y 10 000 solicitudes simultáneas.
- **Accesibilidad**: Cumplir con WCAG 2.1 AA para todas las interfaces.
- **Compatibilidad**: Navegadores modernos (Chrome, Edge, Firefox, Safari).

## 5. Restricciones Tecnológicas
- Backend basado en **PHP 8.2** con framework **Laravel 10** y **Livewire**.
- Base de datos **MySQL**.
- Frontend con **Vue 3** y **AlpineJS** (sin Tailwind).
- Autenticación mediante **SimpleSAMLphp** (federación de identidad universitaria).

## 6. Suposiciones
- Los administradores de escenario ya están asociados a uno o varios escenarios en la base de datos.
- La universidad proveerá la configuración de SimpleSAML y los metadatos de IdP.
- No se requieren integraciones externas adicionales en la fase MVP.

## 7. Próximos pasos
- Validar los requerimientos con los stakeholders.
- Definir prioridades y plan de sprints (ver backlog adjunto).

---
*Este documento se generó automáticamente a partir de la información proporcionada.*
|
