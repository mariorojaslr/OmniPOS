<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |----------------------------------------------------------------------
        | EMPRESA ACTUAL DEL USUARIO
        |----------------------------------------------------------------------
        | Eliminamos dependencia de session('empresa') que provocaba 403
        | Ahora siempre usamos la empresa del usuario autenticado
        */
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        if (!$empresaId) {
            abort(403, 'Usuario sin empresa asignada');
        }

        /*
        |----------------------------------------------------------------------
        | BLOQUE COMERCIAL
        |----------------------------------------------------------------------
        */

        $ventasHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereDate('created_at', today())
            ->sum('total_con_iva');

        $ventasMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereMonth('created_at', now()->month)
            ->sum('total_con_iva');

        $cantidadVentasHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereDate('created_at', today())
            ->count();


        /*
        |----------------------------------------------------------------------
        | BLOQUE GESTIÓN
        |----------------------------------------------------------------------
        */

        $usuariosCount = User::where('empresa_id', $empresaId)->count();

        $clientesCount = Client::where('empresa_id', $empresaId)->count();


        /*
        |----------------------------------------------------------------------
        | BLOQUE OPERATIVO
        |----------------------------------------------------------------------
        */

        $productosCount = Product::where('empresa_id', $empresaId)->count();

        $stockBajo = Product::where('empresa_id', $empresaId)
            ->whereColumn('stock_actual', '<=', 'stock_min')
            ->count();


        /*
        |----------------------------------------------------------------------
        | RENDER DASHBOARD
        |----------------------------------------------------------------------
        */

        return view('empresa.dashboard.index', [
            'empresa' => $user->empresa,   // relación Eloquent
            'ventasHoy' => $ventasHoy,
            'ventasMes' => $ventasMes,
            'cantidadVentasHoy' => $cantidadVentasHoy,
            'usuariosCount' => $usuariosCount,
            'clientesCount' => $clientesCount,
            'productosCount' => $productosCount,
            'stockBajo' => $stockBajo,
        ]);
    }
}
