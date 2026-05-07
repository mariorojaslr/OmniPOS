<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

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
        | EXCEPCIONES: OWNER O USUARIO DE DEMO SIEMPRE PASAN
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'owner' || session('impersonator_id') || $user->email === 'demo@multipos.system') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFICACIÓN DE ONBOARDING (PAY-BEFORE-CREATE)
        | Solo aplica a dueños que están en proceso de alta inicial y no tienen empresa.
        |--------------------------------------------------------------------------
        */
        if (($user->esProspecto() || $user->pendientePago()) && !$user->empresa_id) {
            if (!$request->routeIs('register.pay') && !$request->routeIs('register.payment.store') && !$request->routeIs('logout.get') && !$request->routeIs('demo.mode')) {
                return redirect()->route('register.pay');
            }
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFICACIONES DE MULTITENENCIA (REGLAS DE SEGURIDAD)
        |--------------------------------------------------------------------------
        */
        $empresa = $user->empresa;

        if (!$empresa) {
            // Si el usuario es ACTIVO pero no tiene empresa, debe ir a crearla
            if ($user->status === 'activo') {
                if (!$request->routeIs('register.company') && !$request->routeIs('register.company.store') && !$request->routeIs('logout.get')) {
                    return redirect()->route('register.company');
                }
                return $next($request);
            }

            // Para cualquier otro caso sin empresa, logout por seguridad.
            Auth::logout();
            return redirect()->route('login')->with('error', 'Su cuenta requiere configuración administrativa.');
        }

        // 1. Empresa Inactiva
        if (!$empresa->activo) {
            abort(403, 'SU EMPRESA SE ENCUENTRA INACTIVA. POR FAVOR CONTACTE A SOPORTE.');
        }

        // 2. Empresa Vencida Definitiva (> 24 Hs)
        if ($empresa->estaVencidaTotalmente()) {
            abort(403, 'EL SERVICIO HA SIDO SUSPENDIDO POR FALTA DE PAGO. SU CÓDIGO BANCARIO ESTÁ EN SU PANEL PRINCIPAL. REALICE EL PAGO Y SUBA EL COMPROBANTE PARA REACTIVAR.');
        }

        // 3. Empresa en Período de Gracia (Vencida, pero dentro de las 24 Hs)
        if ($empresa->estaEnPeriodoDeGracia()) {
            session()->now('warning', '⚠️ ATENCIÓN URGENTE: Su ciclo de facturación se ha vencido. Tiene 24 horas de gracia para operar. Por favor renueve inmediatamente para evitar el corte total.');
        }

        // 3. Usuario Inactivo
        if (!$user->estaActivo()) {
            abort(403, 'SU USUARIO HA SIDO DESACTIVADO POR EL ADMINISTRADOR DE SU EMPRESA.');
        }

        // 4. (Opcional) Cambio de contraseña obligatoria
        /*
        if ($user->must_change_password) {
            // Nota: Podría redirigirse a una ruta de cambio de contraseña específica aquí
        }
        */

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
