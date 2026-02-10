<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioDashboardController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $empresaId = $user->empresa_id;
        $userId    = $user->id;

        /*
        |--------------------------------------------------------------------------
        | MIS VENTAS HOY
        |--------------------------------------------------------------------------
        */
        $ventasHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('total_con_iva');

        $cantidadHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | MIS VENTAS MES
        |--------------------------------------------------------------------------
        */
        $ventasMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_con_iva');

        $cantidadMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('empresa.usuario.dashboard', compact(
            'ventasHoy',
            'cantidadHoy',
            'ventasMes',
            'cantidadMes'
        ));
    }
}
