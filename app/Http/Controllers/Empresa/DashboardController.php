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
        try {
            $user = Auth::user();
            $empresaId = $user->empresa_id;
            $empresa = $user->empresa;

            // Inicializamos todo en cero por seguridad
            $ventasHoy = 0; $ventasMes = 0; $cantidadVentasHoy = 0;
            $usuariosCount = 0; $clientesCount = 0; $productosCount = 0; $stockBajo = 0;
            $pedidosPendientes = 0; $pedidosTotales = 0;
            $gastosMes = 0; $comprasMes = 0; $ventasInternetHoy = 0;

            // Intentamos cargar métricas una por una
            try { $ventasHoy = DB::table('ventas')->where('empresa_id', $empresaId)->whereDate('created_at', today())->sum('total_con_iva'); } catch(\Exception $e){}
            try { $ventasMes = DB::table('ventas')->where('empresa_id', $empresaId)->whereMonth('created_at', now()->month)->sum('total_con_iva'); } catch(\Exception $e){}
            try { $cantidadVentasHoy = DB::table('ventas')->where('empresa_id', $empresaId)->whereDate('created_at', today())->count(); } catch(\Exception $e){}
            
            try { $usuariosCount = User::where('empresa_id', $empresaId)->count(); } catch(\Exception $e){}
            try { $clientesCount = Client::where('empresa_id', $empresaId)->count(); } catch(\Exception $e){}
            try { $productosCount = Product::where('empresa_id', $empresaId)->count(); } catch(\Exception $e){}
            try { $stockBajo = Product::where('empresa_id', $empresaId)->where(fn($q) => $q->whereColumn('stock', '<=', 'stock_min')->orWhere('stock', '<=', 0))->count(); } catch(\Exception $e){}

            try { $pedidosPendientes = DB::table('orders')->where('empresa_id', $empresaId)->whereIn('estado', ['pendiente', 'en_proceso', 'pedido_armado'])->count(); } catch(\Exception $e){}
            try { $pedidosTotales = DB::table('orders')->where('empresa_id', $empresaId)->count(); } catch(\Exception $e){}

            try { $gastosMes = DB::table('expenses')->where('empresa_id', $empresaId)->whereMonth('created_at', now()->month)->sum('amount'); } catch(\Exception $e){}
            try { $comprasMes = DB::table('purchases')->where('empresa_id', $empresaId)->whereMonth('created_at', now()->month)->sum('total'); } catch(\Exception $e){}
            try { $ventasInternetHoy = DB::table('orders')->where('empresa_id', $empresaId)->whereDate('created_at', today())->sum('total'); } catch(\Exception $e){}

            $reminders = [];
            $maxCanal = max($ventasHoy, $ventasInternetHoy, 1000);
            $saludVentasLocal = round(($ventasHoy / $maxCanal) * 100);
            $saludVentasInternet = round(($ventasInternetHoy / $maxCanal) * 100);

            $totalEgresos = $gastosMes + $comprasMes;
            $balanceLocal = $ventasHoy - $totalEgresos;
            $gastosPerc = $totalEgresos > 0 ? round(($gastosMes / $totalEgresos) * 100) : 0;
            $comprasPerc = $totalEgresos > 0 ? round(($comprasMes / $totalEgresos) * 100) : 0;
            $evaluacionLocal = $ventasHoy > 0 ? min(100, round(($ventasHoy / ($totalEgresos + 1)) * 50)) : ($totalEgresos > 0 ? 5 : 0);

            return view('empresa.dashboard.index', [
                'empresa' => $empresa,
                'ventasLocalHoy' => $ventasHoy,
                'ventasInternetHoy' => $ventasInternetHoy,
                'gastosHoy' => $gastosMes,
                'comprasHoy' => $comprasMes,
                'ventasHoy' => $ventasHoy + $ventasInternetHoy,
                'ventasMes' => $ventasMes,
                'cantidadVentasHoy' => $cantidadVentasHoy,
                'usuariosCount' => $usuariosCount,
                'clientesCount' => $clientesCount,
                'productosCount' => $productosCount,
                'stockBajo' => $stockBajo,
                'pedidosPendientes' => $pedidosPendientes,
                'pedidosTotales' => $pedidosTotales,
                'reminders' => [],
                'saludLocal' => $saludVentasLocal,
                'saludInternet' => $saludVentasInternet,
                'balanceLocal' => $balanceLocal,
                'evaluacionLocal' => $evaluacionLocal,
                'gastosPerc' => $gastosPerc,
                'comprasPerc' => $comprasPerc,
                'recentActivities' => collect(),
            ]);

        } catch (\Throwable $e) {
            return "❌ ERROR CRITICO EN DASHBOARD: " . $e->getMessage();
        }
    }

    public function novedades()
    {
        return view('empresa.updates.index', ['updates' => collect()]);
    }
}
