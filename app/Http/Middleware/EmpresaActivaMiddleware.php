<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmpresaActivaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Seguridad básica
        if (!$user || !$user->empresa) {
            abort(403, 'EMPRESA INACTIVA');
        }

        $empresa = $user->empresa;

        // Empresa desactivada
        if ((int) $empresa->activo !== 1) {
            abort(403, 'EMPRESA INACTIVA');
        }

        // Empresa vencida
        if ($empresa->fecha_vencimiento && now()->gt($empresa->fecha_vencimiento)) {
            abort(403, 'EMPRESA VENCIDA');
        }

        return $next($request);
    }
}
