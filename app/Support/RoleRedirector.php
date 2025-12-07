<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class RoleRedirector
{
    /**
     * Mapa de roles a sus rutas principales.
     */
    private const ROLE_ROUTES = [
        'ADMIN'       => 'admin.dashboard',
        'AUDITOR'     => 'auditor.dashboard',
        'CREADOR'     => 'creador.dashboard',
        'COLABORADOR' => 'colaborador.dashboard',
    ];

    /**
     * Devuelve la redireccion al dashboard correspondiente al primer rol encontrado.
     */
    public static function redirect(User $user): ?RedirectResponse
    {
        $user->loadMissing('roles');

        foreach ($user->roles as $role) {
            $name = strtoupper($role->nombre_rol);

            if (isset(self::ROLE_ROUTES[$name])) {
                return redirect()->route(self::ROLE_ROUTES[$name]);
            }
        }

        return null;
    }
}
