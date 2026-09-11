# Tarea: modelo-espacios-reservas

**Creada:** 2026-09-11 · **Tamaño:** medium · **Owner:** (sin asignar) · **Rol:** (sin asignar)

## Objetivo
Definir e implementar los modelos Eloquent, migraciones, relaciones y validaciones de negocio para Escenarios (`Space`) y Reservas (`Booking`), incluyendo la prevención de colisiones horarias y seeders iniciales.

## Incluido en el alcance
- Migración y modelo `Space` con atributos (`code`, `name`, `type`, `capacity`, `location`, `is_active`).
- Migración y modelo `Booking` con atributos (`space_id`, `user_id`, `date`, `start_time`, `end_time`, `subject`, `status`).
- Adaptación de `User` con campo `role` (`docente`, `admin`, `coordinador`).
- Relaciones Eloquent entre `User`, `Space` y `Booking`.
- Regla/método de consulta para detección de solapamientos horarios (`isAvailable` o query scope de colisión).
- Factories y Seeders (`SpaceFactory`, `BookingFactory`, `SpacesTableSeeder`).
- Pruebas automatizadas en `tests/Feature/SpaceBookingModelTest.php`.

## Explícitamente fuera de alcance
- Interfaz de usuario (componentes Livewire, vistas Blade o calendario visual).
- Controladores HTTP o rutas web/API finales.
- Autenticación o flujos de inicio de sesión.

## Write-set (archivos que se espera tocar)
No editar fuera de esta lista sin actualizarla primero. Evita refactors oportunistas.

- `database/migrations/*_create_spaces_table.php`
- `database/migrations/*_create_bookings_table.php`
- `database/migrations/*_add_role_to_users_table.php`
- `app/Models/Space.php`
- `app/Models/Booking.php`
- `app/Models/User.php`
- `database/factories/SpaceFactory.php`
- `database/factories/BookingFactory.php`
- `database/seeders/SpacesTableSeeder.php`
- `database/seeders/DatabaseSeeder.php`
- `tests/Feature/SpaceBookingModelTest.php`

## Fuentes de verdad a leer antes de empezar
- `.ai/state/estado-dev.md`
- `.ai/state/topics/arquitectura.md`
- `.ai/state/topics/resumen.md`

## Contexto mínimo sugerido
Según el tamaño declarado arriba, no cargues más de lo necesario:

| Tamaño  | Techo de lectura inicial orientativo |
|---------|----------------------------------------|
| small   | 1-3 archivos concretos, sin explorar carpetas completas |
| medium  | 1 módulo/dominio, usar `rg`/`grep` para localizar antes de leer |
| large   | fragmentar con `continuum packetize`; considerar dividir en subtareas |
