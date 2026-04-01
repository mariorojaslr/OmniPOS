<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmpresaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // ❌ Sin login → prohibido
        if (!$user) {
            abort(403, 'Usuario no autenticado');
        }

        /*
        |--------------------------------------------------------------------------
        | OWNER PUEDE ENTRAR A ZONA EMPRESA SI ESTÁ SUPERVISANDO O MIMETIZADO
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'owner') {
             // Si el owner está revisando, lo dejamos pasar.
             return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | EMPRESA Y USUARIO → OK
        |--------------------------------------------------------------------------
        */
        if (in_array($user->role, ['empresa', 'usuario'])) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | CUALQUIER OTRO CASO → BLOQUEADO
        |--------------------------------------------------------------------------
        */
        abort(403, 'Acceso no permitido');
    }
}
