<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmpresaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'No autenticado');
        }

        if (auth()->user()->empresa_id === null) {
            abort(403, 'Acceso solo para empresa');
        }

        return $next($request);
    }
}
