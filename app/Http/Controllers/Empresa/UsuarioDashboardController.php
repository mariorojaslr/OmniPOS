<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioDashboardController extends Controller
{
    public function index()
    {
        // ================= USUARIO ACTUAL =================
        $user = Auth::user();

        // Seguridad: si no tiene empresa, bloquear acceso
        if (!$user || !$user->empresa_id) {
            abort(403);
        }

        $empresaId = $user->empresa_id;

        /*
        |--------------------------------------------------------------------------
        | IMPORTANTE
        |--------------------------------------------------------------------------
        | El sistema ahora usa SIEMPRE:
        | ventas.total_con_iva
        | (estructura nueva ya alineada en todas las bases)
        |--------------------------------------------------------------------------
        */

        // ================= VENTAS HOY =================
        $ventasHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('total_con_iva');

        $cantidadVentasHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        // ================= VENTAS DEL MES =================
        $ventasMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_con_iva');

        $cantidadVentasMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ================= RETORNAR VISTA =================
        return view('empresa.usuario.dashboard', [
            'empresa' => $user->empresa,
            'ventasHoy' => (float) $ventasHoy,
            'ventasMes' => (float) $ventasMes,
            'cantidadVentasHoy' => $cantidadVentasHoy,
            'cantidadVentasMes' => $cantidadVentasMes,
        ]);
    }
}
