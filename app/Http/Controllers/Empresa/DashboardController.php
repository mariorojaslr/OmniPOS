<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Empresa;

class DashboardController extends Controller
{
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        // ================= EMPRESA =================
        $empresa = Empresa::find($empresaId);

        // ================= CONTADORES BASICOS =================
        $usuariosCount  = User::where('empresa_id', $empresaId)->count();
        $productosCount = Product::where('empresa_id', $empresaId)->count();

        // ================= VENTAS HOY =================
        $ventasHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereDate('created_at', today())
            ->sum('total_con_iva');

        $cantidadVentasHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereDate('created_at', today())
            ->count();

        // ================= VENTAS MES =================
        $ventasMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_con_iva');

        $cantidadVentasMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ================= STOCK BAJO (SI EXISTE COLUMNA) =================
        $stockBajo = 0;
        if (\Schema::hasColumn('products', 'stock')) {
            $stockBajo = Product::where('empresa_id', $empresaId)
                ->where('stock', '<=', 5)
                ->count();
        }

        return view('empresa.dashboard', compact(
            'empresa',
            'usuariosCount',
            'productosCount',
            'ventasHoy',
            'ventasMes',
            'cantidadVentasHoy',
            'cantidadVentasMes',
            'stockBajo'
        ));
    }
}
