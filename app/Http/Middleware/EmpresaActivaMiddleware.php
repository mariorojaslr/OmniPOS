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

        /*
        |--------------------------------------------------------------------------
        | SIN USUARIO → BLOQUEAR
        |--------------------------------------------------------------------------
        */
        if (!$user) {
            abort(403, 'USUARIO NO AUTENTICADO');
        }

        /*
        |--------------------------------------------------------------------------
        | OWNER SIEMPRE PASA
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'owner') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 🔓 MODO DESARROLLO — TODO PERMITIDO
        |--------------------------------------------------------------------------
        | TEMPORAL:
        | - No bloquea empresa inactiva
        | - No bloquea empresa vencida
        | - No bloquea must_change_password
        | - No bloquea nada
        |
        | OBJETIVO:
        | PODER ENTRAR Y SEGUIR DESARROLLANDO
        |
        | CUANDO TERMINEMOS:
        | VOLVEMOS A ACTIVAR SEGURIDAD
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}
