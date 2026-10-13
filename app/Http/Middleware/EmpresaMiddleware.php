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
             // Si el owner no tiene empresa asignada, vincular con la clínica de prueba
             // para que pueda navegar todos los sectores (turnos, historias, etc.) fluidamente.
             if (!$user->empresa_id) {
                 $empresaDefault = \App\Models\Empresa::where('slug', 'clinica-dental-prueba')->first()
                                ?? \App\Models\Empresa::where('tipo_clinica', 'clinica_odontologica')->first()
                                ?? \App\Models\Empresa::first();
                 if ($empresaDefault) {
                     $user->empresa_id = $empresaDefault->id;
                     $user->setRelation('empresa', $empresaDefault);
                 }
             }
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
