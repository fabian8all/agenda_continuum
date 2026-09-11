# Plan de ejecución: modelo-espacios-reservas

Solo para tareas `medium`/`large`. Es una cola persistente de pasos: márcalos
al avanzar para que, si la sesión se corta a medio camino, la siguiente sepa
exactamente dónde retomar sin releer todo desde cero.

- [x] Paso 1 — Crear migraciones para `spaces`, `bookings` y campo `role` en `users`.
- [x] Paso 2 — Crear modelos Eloquent `Space` y `Booking` y actualizar `User` con relaciones y casts.
- [x] Paso 3 — Implementar lógica de validación de colisiones horarias en `Booking` / `Space` (scope `overlapping` + `Space::isAvailable`).
- [x] Paso 4 — Crear factories y seeders (`SpaceFactory`, `BookingFactory`, `SpacesTableSeeder`).
- [x] Paso 5 — Escribir suite de pruebas (`tests/Feature/SpaceBookingModelTest.php`) y validar con `php artisan test`.
- [ ] Paso 6 — Ejecutar `tools/continuum doctor`, redactar handoff y cerrar la tarea (`tools/continuum task close modelo-espacios-reservas`).

## Estado actual
Pasos 1-5 completos. Falta levantar Sail (no hay `php` en el host) para correr
`php artisan test` y confirmar en verde, luego doctor + handoff + cierre.

