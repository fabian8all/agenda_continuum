# Pruebas de accesibilidad (axe-core + Playwright)

Auditan las páginas reales de la app contra las reglas WCAG 2.0/2.1 A y AA
usando [axe-core](https://github.com/dequelabs/axe-core). Son un check
adicional, aparte de la suite de PHPUnit (`sail artisan test`).

## Requisitos

- `./vendor/bin/sail up -d` corriendo.
- `SAML_SIMULATOR=true` en `.env` (el flujo de login se resuelve solo con
  visitar la página, sin interacción manual).
- Dependencias instaladas: `./vendor/bin/sail npm install` y
  `./vendor/bin/sail exec laravel.test npx playwright install --with-deps chromium`
  (una sola vez; descarga Chromium y sus librerías del sistema).

## Correrlas

```
./vendor/bin/sail npm run test:a11y
```

## Qué hacen

1. `global-setup.js` promueve al usuario del simulador de SAML
   (`SAML_SIMULATOR_EMAIL`) a `coordinador`, para poder auditar también las
   páginas restringidas a ese rol (`/solicitudes`,
   `/administracion/usuarios`, `/administracion/auditoria`). **Esto
   modifica la base de datos de desarrollo real**, no una de pruebas — no
   corren contra una BD aislada como `sail artisan test`.
2. `pages.spec.js` visita cada página pública y protegida (el login se
   resuelve automáticamente al visitarlas, vía el simulador) y falla si
   `axe-core` encuentra alguna violación de nivel A o AA. También verifica
   que el menú de navegación se colapse correctamente en viewport móvil.

## Al agregar una página nueva

Agrégala al arreglo `PAGES` en `pages.spec.js` para que quede cubierta.
