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
        | OWNER NO DEBE ENTRAR A ZONA EMPRESA
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'owner') {
            abort(403, 'Owner no puede entrar a zona empresa');
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
