<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Empresa;

class EmpresaActivaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // -------------------------------------------------
        // Verificar usuario logueado
        // -------------------------------------------------
        if (!$user) {
            abort(403, 'USUARIO NO AUTENTICADO');
        }

        // -------------------------------------------------
        // Buscar empresa directamente por ID (más seguro)
        // -------------------------------------------------
        $empresa = Empresa::find($user->empresa_id);

        if (!$empresa) {
            abort(403, 'EMPRESA NO EXISTE');
        }

        // -------------------------------------------------
        // Empresa desactivada
        // -------------------------------------------------
        if ((int) $empresa->activo !== 1) {
            abort(403, 'EMPRESA INACTIVA');
        }

        // -------------------------------------------------
        // Empresa vencida
        // -------------------------------------------------
        if ($empresa->fecha_vencimiento && now()->gt($empresa->fecha_vencimiento)) {
            abort(403, 'EMPRESA VENCIDA');
        }

        return $next($request);
    }
}
