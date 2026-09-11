# Comandos de referencia rápida

```bash
# Iniciar entorno con Laravel Sail
./vendor/bin/sail up -d

# Detener entorno Sail
./vendor/bin/sail stop

# Ejecutar comandos Artisan
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan test

# Compilación y Frontend
./vendor/bin/sail npm run dev
./vendor/bin/sail npm run build

# Ejecución standalone con contenedor PHP 8.2 (sin Sail levantado)
docker run --rm -u "$(id -u):$(id -g)" -e HOME=/tmp -v $(pwd):/var/www/html -w /var/www/html laravelsail/php82-composer:latest php artisan test

# Continuum
tools/continuum doctor
tools/continuum status
tools/continuum handoff
```
