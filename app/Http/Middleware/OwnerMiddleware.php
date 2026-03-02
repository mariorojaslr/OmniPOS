<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar autenticación
        if (!auth()->check()) {
            abort(403, 'Usuario no autenticado');
        }

        $user = auth()->user();

        // Verificar rol OWNER (case insensitive)
        if (!isset($user->role) || strtolower($user->role) !== 'owner') {
            abort(403, 'Acceso solo para Owner');
        }

        return $next($request);
    }
}
