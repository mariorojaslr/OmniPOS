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
        | OWNER O MIMETIZADO SIEMPRE PASA (Omnisciencia)
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'owner' || session('impersonator_id')) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFICACIONES DE MULTITENENCIA (REGLAS DE SEGURIDAD)
        |--------------------------------------------------------------------------
        */
        $empresa = $user->empresa;

        if (!$empresa) {
            abort(403, 'SU USUARIO NO TIENE UNA EMPRESA ASIGNADA.');
        }

        // 1. Empresa Inactiva
        if (!$empresa->activo) {
            abort(403, 'SU EMPRESA SE ENCUENTRA INACTIVA. POR FAVOR CONTACTE A SOPORTE.');
        }

        // 2. Empresa Vencida
        if ($empresa->estaVencida()) {
            abort(403, 'LA SUSCRIPCIÓN DE SU EMPRESA HA VENCIDO. POR FAVOR RENUEVE SU PLAN.');
        }

        // 3. Usuario Inactivo
        if (!$user->estaActivo()) {
            abort(403, 'SU USUARIO HA SIDO DESACTIVADO POR EL ADMINISTRADOR DE SU EMPRESA.');
        }

        // 4. (Opcional) Cambio de contraseña obligatoria
        if ($user->must_change_password) {
            // Nota: Podría redirigirse a una ruta de cambio de contraseña específica aquí
        }

        /*
        |--------------------------------------------------------------------------
        | SINCRONIZACIÓN DE ZONA HORARIA (RELOJ SUIZO)
        |--------------------------------------------------------------------------
        */
        $emp_tz = $user->empresa;
        if ($emp_tz && $emp_tz->timezone) {
            config(['app.timezone' => $emp_tz->timezone]);
            date_default_timezone_set($emp_tz->timezone);
        }

        return $next($request);
    }
}
