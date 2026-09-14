import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

// Requiere autenticarse (el simulador de SAML crea la sesión con solo
// visitar la ruta protegida, ver App\Http\Middleware\VerifyAuthSaml).
const PAGES = [
    { name: 'Inicio', path: '/' },
    { name: 'Escenarios', path: '/escenarios' },
    { name: 'Calendario', path: '/calendario' },
    { name: 'Nueva solicitud', path: '/solicitudes/nueva' },
    { name: 'Gestión de solicitudes', path: '/solicitudes' },
    { name: 'Administración de usuarios', path: '/administracion/usuarios' },
    { name: 'Auditoría', path: '/administracion/auditoria' },
];

for (const { name, path } of PAGES) {
    test(`${name}: sin violaciones WCAG 2.1 A/AA`, async ({ page }) => {
        await page.goto(path);

        const results = await new AxeBuilder({ page })
            .withTags(['wcag2a', 'wcag2aa', 'wcag21aa'])
            .analyze();

        expect(results.violations, formatViolations(results.violations)).toEqual([]);
    });
}

test('el menú de navegación se colapsa en móvil y el botón lo despliega', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');

    const toggler = page.getByRole('button', { name: 'Abrir menú de navegación' });
    const nav = page.locator('#main-nav');

    await expect(toggler).toBeVisible();
    await expect(nav).not.toBeVisible();

    await toggler.click();

    await expect(nav).toBeVisible();
    await expect(nav.getByRole('link', { name: 'Escenarios', exact: true })).toBeVisible();
});

test('el menú de navegación no muestra el botón de colapso en escritorio', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 800 });
    await page.goto('/');

    await expect(page.getByRole('button', { name: 'Abrir menú de navegación' })).not.toBeVisible();
    await expect(page.locator('#main-nav').getByRole('link', { name: 'Escenarios', exact: true })).toBeVisible();
});

function formatViolations(violations) {
    return violations
        .map((v) => `${v.id} (${v.impact}): ${v.help} — ${v.nodes.length} nodo(s)\n  ${v.helpUrl}`)
        .join('\n');
}
