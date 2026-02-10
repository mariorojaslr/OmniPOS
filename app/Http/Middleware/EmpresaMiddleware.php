<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmpresaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // OWNER no entra
        if ($user->role === 'owner') {
            abort(403);
        }

        // EMPRESA y USUARIO pueden usar TODO (sin perder funciones)
        if (in_array($user->role, ['empresa', 'usuario'])) {
            return $next($request);
        }

        abort(403);
    }
}
