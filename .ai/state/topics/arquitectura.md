# Arquitectura y dominio

## Entidades y Modelos (Eloquent / MySQL)

    1. **`User` (Docentes, Administradores y Responsables de aula):**                                                                                                                                                     
       - Campos: `id`, `name`, `email`, `role` (enum: `docente`, `admin`, `coordinador`).                                                                                                           
       - Relación: `hasMany(Booking::class)`.                                                                                                                                                       
                                                                                                                                                                                                    
    2. **`Space` (Escenario / Aula):**                                                                                                                                                              
       - Campos: `id`, `code` (ej: 'A-201'), `name`, `type` (aula_teorica, laboratorio, auditorio), `capacity` (int), `location` (edificio/piso), `is_active` (boolean).                            
       - Relación: `hasMany(Booking::class)`.                                                                                                                                                       
                                                                                                                                                                                                    
    3. **`Booking` (Reserva de aula):**                                                                                                                                                             
       - Campos: `id`, `space_id` (FK), `user_id` (FK), `date` (date), `start_time` (time), `end_time` (time), `subject` (materia/evento), `status` (enum: `confirmada`, `cancelada`, `pendiente`). 
                                                                                                                                                                                                    
    ## Reglas de negocio e Invariantes                                                                                                                                                              
                                                                                                                                                                                                    
    - **Regla de oro (Sin colisiones):** Ningún `Space` puede tener dos reservas con `status != 'cancelada'` cuyo rango horario se solape en la misma fecha:                                        
      `start_time < existing.end_time AND end_time > existing.start_time`.                                                                                                                          
    - **Rango de anticipación:** Los docentes solo pueden reservar con un margen de entre 2 horas y 15 días continuos.                                                                              
    - **Control de aforo:** El número esperado de alumnos no puede superar `Space.capacity`.                                                                                                        
                                                                                                                                                                                                    
    ## Patrón de Interfaz (Livewire + Alpine.js)                                                                                                                                                    
                                                                                                                                                                                                    
    - **Alpine.js:** Encargado exclusivamente de interactividad local y UI inmediata sin consultar al servidor: apertura/cierre de modales, selectores de fecha/hora, tooltips y tabs.              
    - **Livewire v3:** Encargado de la lógica reactiva que requiere acceso a base de datos: renderizado del calendario/cuadrícula de disponibilidad, validación de reglas de negocio en tiempo real 
y persistencia de la reserva.                                                                                                                                                                     
- Componentes principales previstos:                                                                                                                                                            
- `App\Livewire\Bookings\ScheduleCalendar`: Cuadrícula visual de aulas vs horas.                                                                                                              
- `App\Livewire\Bookings\CreateBookingModal`: Formulario reactivo con validación de traslape en tiempo real.      
