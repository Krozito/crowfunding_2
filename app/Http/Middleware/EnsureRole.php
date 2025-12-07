<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\RoleRedirector;

class EnsureRole
{
    /**
     * Uso en rutas: ->middleware('role:ADMIN') o ->middleware('role:ADMIN,AUDITOR')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401); // no autenticado
        }

        // Asegurate de tener $user->roles() definido y el helper hasRole
        $user->loadMissing('roles');

        $tieneRol = collect($roles)->some(function ($rol) use ($user) {
            return $user->roles->contains(fn($r) => strcasecmp($r->nombre_rol, $rol) === 0);
        });

        if (!$tieneRol) {
            // Si no tiene permiso aqui pero si otro rol, lo redirigimos a su panel correspondiente
            if ($redirect = RoleRedirector::redirect($user)) {
                return $redirect;
            }

            abort(403); // prohibido y sin panel asociado
        }

        return $next($request);
    }
}
