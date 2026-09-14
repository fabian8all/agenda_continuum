import { defineConfig } from '@playwright/test';

// Pruebas de accesibilidad (axe-core) contra la app real corriendo en
// Sail. Requiere `./vendor/bin/sail up -d` y SAML_SIMULATOR=true en
// .env — ver tests/accessibility/README.md.
export default defineConfig({
    testDir: './tests/accessibility',
    fullyParallel: false,
    retries: 0,
    reporter: 'list',
    use: {
        // Corre dentro del contenedor de Sail (`sail npm run test:a11y`),
        // donde la app escucha en el puerto 80 interno, no en el 8090
        // publicado al host.
        baseURL: process.env.APP_URL_TEST ?? 'http://localhost',
        screenshot: 'only-on-failure',
    },
    globalSetup: './tests/accessibility/global-setup.js',
});
