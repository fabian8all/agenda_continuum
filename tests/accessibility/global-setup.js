import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { execSync } from 'node:child_process';

/**
 * Antes de correr las pruebas, promueve al usuario que usa el simulador de
 * SAML (SAML_SIMULATOR_EMAIL) a 'coordinador', para poder auditar también
 * las páginas restringidas a ese rol (/solicitudes, /administracion/*).
 *
 * Esto modifica la base de datos de desarrollo real (no una de pruebas):
 * estas pruebas están pensadas para correr contra el entorno de Sail local,
 * no como parte de `sail artisan test`. Ver tests/accessibility/README.md.
 */
export default async function globalSetup() {
    const envContent = fs.readFileSync('.env', 'utf8');
    const email = (envContent.match(/^SAML_SIMULATOR_EMAIL=(.*)$/m)?.[1] ?? 'docente@ucol.mx')
        .replace(/^"|"$/g, '')
        .trim();

    const script = [
        '<?php',
        'use App\\Models\\User;',
        `User::updateOrCreate(['email' => '${email}'], ['name' => 'Usuario de prueba a11y', 'password' => bcrypt('secret'), 'role' => 'coordinador']);`,
        'echo "listo";',
    ].join('\n');

    const tmpFile = path.join(os.tmpdir(), 'a11y-seed.php');
    fs.writeFileSync(tmpFile, script);

    execSync(`php artisan tinker ${tmpFile}`, { stdio: 'inherit' });
}
