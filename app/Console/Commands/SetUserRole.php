<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetUserRole extends Command
{
    /**
     * Sirve para promover al primer 'coordinador' (Administrador General):
     * el aprovisionamiento vía SAML siempre crea usuarios con rol
     * 'docente' por defecto, así que sin este comando nadie podría llegar
     * nunca a /administracion/usuarios.
     *
     * @var string
     */
    protected $signature = 'users:set-role {email} {role}';

    protected $description = 'Asigna un rol (docente, admin, coordinador) a un usuario existente por email';

    public function handle(): int
    {
        $role = $this->argument('role');

        if (! in_array($role, ['docente', 'admin', 'coordinador'], true)) {
            $this->error("Rol inválido: {$role}. Usa: docente, admin o coordinador.");

            return self::FAILURE;
        }

        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error("No existe ningún usuario con el email {$this->argument('email')}.");

            return self::FAILURE;
        }

        $user->update(['role' => $role]);

        $this->info("{$user->email} ahora tiene el rol '{$role}'.");

        return self::SUCCESS;
    }
}
