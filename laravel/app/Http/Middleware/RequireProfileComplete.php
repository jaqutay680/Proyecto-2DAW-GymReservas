<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireProfileComplete
{
    /**
     * Si el usuario está autenticado pero su perfil no está completo
     * (profile_completed = 0), lo redirige a /completar-perfil.
     * Se excluyen las rutas de completar-perfil y logout para evitar bucles.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $completed = $user->profile_completed ?? 1;

            if (!$completed) {
                // Evitar bucle de redirección
                if (!$request->routeIs('profile.complete.*') && !$request->routeIs('logout')) {
                    return redirect()->route('profile.complete.form');
                }
            }
        }

        return $next($request);
    }
}
