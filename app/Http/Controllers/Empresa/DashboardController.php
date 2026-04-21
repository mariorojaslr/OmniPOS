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
            
            // Evadir if role = usuario
            if ($user->role === 'usuario') {
                return redirect()->route('empresa.usuario.dashboard');
            }

            $empresaId = $user->empresa_id;

            if (!$empresaId) {
                // Si es un Owner mimetizado sin empresa_id (raro pero posible), intentar recuperar del mimetizador
                if (session('impersonator_id')) {
                    $owner = \App\Models\User::find(session('impersonator_id'));
                    if ($owner) {
                        Auth::login($owner);
                        session()->forget('impersonator_id');
                        return redirect()->route('owner.dashboard')->with('error', 'Error crítico: La empresa a la que intentó entrar no tiene ID válido.');
                    }
                }
                abort(403, 'Usuario sin empresa asignada');
            }

            $empresa = $user->empresa;
            if (!$empresa) {
                abort(404, 'Empresa no encontrada');
            }

            /* Métricas Comerciales */
            $ventasHoy = DB::table('ventas')->where('empresa_id', $empresaId)->whereDate('created_at', today())->sum('total_con_iva');
            $ventasMes = DB::table('ventas')->where('empresa_id', $empresaId)->whereMonth('created_at', now()->month)->sum('total_con_iva');
            $cantidadVentasHoy = DB::table('ventas')->where('empresa_id', $empresaId)->whereDate('created_at', today())->count();

            /* Gestión y Operativo */
            $usuariosCount = User::where('empresa_id', $empresaId)->count();
            $clientesCount = Client::where('empresa_id', $empresaId)->count();
            $productosCount = Product::where('empresa_id', $empresaId)->count();
            $stockBajo = Product::where('empresa_id', $empresaId)->where(fn($q) => $q->whereColumn('stock', '<=', 'stock_min')->orWhere('stock', '<=', 0))->count();

            /* Catálogo y Pedidos */
            $pedidosPendientes = \App\Models\Order::where('empresa_id', $empresaId)->whereIn('estado', ['pendiente', 'en_proceso', 'pedido_armado'])->count();
            $pedidosTotales = \App\Models\Order::where('empresa_id', $empresaId)->count();

            /* Alertas y Recordatorios */
            $reminders = [];
            $config = $empresa->config;

            if (!$empresa->cuit || !$empresa->condicion_iva || !$config?->logo_url) {
                $reminders[] = [
                    'type' => 'warning', 'icon' => '⚙️', 'title' => 'Perfil Incompleto', 
                    'message' => 'Por favor, complete sus datos fiscales y suba su logo en Configuración.',
                    'link' => route('empresa.configuracion.index'), 'btn' => 'Ir a Configuración'
                ];
            }

            $comprasCount = \App\Models\Purchase::where('empresa_id', $empresaId)->count();
            if ($productosCount > 0 && $comprasCount === 0) {
                $reminders[] = [
                    'type' => 'info', 'icon' => '📦', 'title' => 'Carga de Stock Pendiente',
                    'message' => 'Recomendamos cargar su stock inicial registrando una Factura de Compra.',
                    'link' => route('empresa.compras.index'), 'btn' => 'Registrar Compra'
                ];
            }

            $reminders[] = [
                'type' => 'danger', 'icon' => '🛡️', 'title' => '¿Encontró un problema?',
                'message' => 'Si detecta cualquier error, por favor genere un Ticket de Soporte.',
                'link' => route('empresa.soporte.index'), 'btn' => 'Generar Ticket'
            ];

            /* Gráficos y Desempeño */
            $gastosMes = \App\Models\Expense::where('empresa_id', $empresaId)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
            $comprasMes = \App\Models\Purchase::where('empresa_id', $empresaId)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total');
            $ventasInternetHoy = \App\Models\Order::where('empresa_id', $empresaId)->whereDate('created_at', today())->sum('total');

            $maxCanal = max($ventasHoy, $ventasInternetHoy, 1000);
            $saludVentasLocal = round(($ventasHoy / $maxCanal) * 100);
            $saludVentasInternet = round(($ventasInternetHoy / $maxCanal) * 100);

            $totalEgresos = $gastosMes + $comprasMes;
            $balanceLocal = $ventasHoy - $totalEgresos;

            $gastosPerc = $totalEgresos > 0 ? round(($gastosMes / $totalEgresos) * 100) : 0;
            $comprasPerc = $totalEgresos > 0 ? round(($comprasMes / $totalEgresos) * 100) : 0;
            $evaluacionLocal = $ventasHoy > 0 ? min(100, round(($ventasHoy / ($totalEgresos + 1)) * 50)) : ($totalEgresos > 0 ? 5 : 0);

            $recentActivities = \App\Models\ActivityLog::where('empresa_id', $empresaId)->with('user')->latest()->limit(15)->get();

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
                'reminders' => $reminders,
                'saludLocal' => $saludVentasLocal,
                'saludInternet' => $saludVentasInternet,
                'balanceLocal' => $balanceLocal,
                'evaluacionLocal' => $evaluacionLocal,
                'gastosPerc' => $gastosPerc,
                'comprasPerc' => $comprasPerc,
                'recentActivities' => $recentActivities,
            ]);

        } catch (\Exception $e) {
            return "❌ ERROR EN CONTROLADOR DE EMPRESA: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine();
        }
    }
    }

    public function novedades()
    {
        $updates = \App\Models\SystemUpdate::orderByDesc('publish_date')->get();
        return view('empresa.updates.index', compact('updates'));
    }
}
