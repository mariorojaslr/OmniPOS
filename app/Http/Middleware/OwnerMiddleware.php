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

        // Verificar rol OWNER (más flexible)
        $role = trim(strtolower($user->role ?? ''));
        if ($role !== 'owner') {
            \Log::warning("Intento de acceso denegado a Owner: " . $user->email . " con rol: " . $user->role);
            abort(403, 'Acceso restringido: Se requiere rol de Owner.');
        }

        return $next($request);
    }
}
