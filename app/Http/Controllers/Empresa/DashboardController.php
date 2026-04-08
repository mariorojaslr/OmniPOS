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
        $user = Auth::user();
        
        // Evadir if role = usuario
        if ($user->role === 'usuario') {
            return redirect()->route('empresa.usuario.dashboard');
        }

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
            ->where(function($q) {
                $q->whereColumn('stock', '<=', 'stock_min')
                  ->orWhere('stock', '<=', 0);
            })
            ->count();


        /*
        |----------------------------------------------------------------------
        | BLOQUE CATALOGO (PEDIDOS)
        |----------------------------------------------------------------------
        */
        $pedidosPendientes = \App\Models\Order::where('empresa_id', $empresaId)
            ->where('estado', 'pendiente')
            ->count();

        $pedidosTotales = \App\Models\Order::where('empresa_id', $empresaId)->count();


        /*
        |----------------------------------------------------------------------
        | SISTEMA DE ALERTAS / RECORDATORIOS (NUEVO)
        |----------------------------------------------------------------------
        */
        $empresa = $user->empresa;
        $reminders = [];

        // 1. Alerta Fiscal/Config
        if (!$empresa->cuit || !$empresa->condicion_iva || !$empresa->config?->logo) {
            $reminders[] = [
                'type' => 'warning',
                'icon' => '⚙️',
                'title' => 'Perfil Incompleto',
                'message' => 'Por favor, complete sus datos fiscales y suba su logo en Configuración.',
                'link' => route('empresa.configuracion.index'),
                'btn' => 'Ir a Configuración'
            ];
        }

        // 2. Alerta Stock Inicial
        $comprasCount = \App\Models\Purchase::where('empresa_id', $empresaId)->count();
        if ($productosCount > 0 && $comprasCount === 0) {
            $reminders[] = [
                'type' => 'info',
                'icon' => '📦',
                'title' => 'Carga de Stock Pendiente',
                'message' => 'Recomendamos cargar su stock inicial creando un proveedor "Carga Inicial" y registrando una Factura de Compra.',
                'link' => route('empresa.compras.index'),
                'btn' => 'Registrar Compra'
            ];
        }

        // 3. Alerta de Reporte de Errores / Soporte
        $reminders[] = [
            'type' => 'danger',
            'icon' => '🛡️',
            'title' => '¿Encontró un problema?',
            'message' => 'Ayúdenos a mejorar: si detecta cualquier error o anomalía, por favor genere un Ticket de Soporte adjuntando capturas de pantalla, comentarios y el detalle del error manifestado.',
            'link' => route('empresa.soporte.index'),
            'btn' => 'Generar Ticket de Soporte'
        ];

        /*
        |----------------------------------------------------------------------
        | RENDER DASHBOARD
        |----------------------------------------------------------------------
        */

        /*
        |----------------------------------------------------------------------
        | MÉTRICAS CANAL LOCAL (POS) - ACUMULADO MES PARA GRÁFICOS
        |----------------------------------------------------------------------
        | Se usa el mes actual para que el gráfico tenga contexto histórico inmediato.
        */
        $ventasLocalHoy = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereDate('created_at', today())
            ->sum('total_con_iva');

        $gastosMes = \App\Models\Expense::where('empresa_id', $empresaId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $comprasMes = \App\Models\Purchase::where('empresa_id', $empresaId)
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total');

        /*
        |----------------------------------------------------------------------
        | MÉTRICAS CANAL INTERNET (CATÁLOGO)
        |----------------------------------------------------------------------
        */
        $ventasInternetHoy = \App\Models\Order::where('empresa_id', $empresaId)
            ->whereDate('created_at', today())
            ->sum('total');

        /*
        |----------------------------------------------------------------------
        | NORMALIZACIÓN PARA GRÁFICOS (MISMA ESCALA)
        |----------------------------------------------------------------------
        */
        $maxCanal = max($ventasLocalHoy, $ventasInternetHoy, 1000); // Mínimo 1000 para escala
        
        $saludVentasLocal = round(($ventasLocalHoy / $maxCanal) * 100);
        $saludVentasInternet = round(($ventasInternetHoy / $maxCanal) * 100);

        // Evaluación Neta (Ventas - Gastos - Compras)
        $totalEgresos = $gastosMes + $comprasMes;
        $balanceLocal = $ventasLocalHoy - $totalEgresos;

        // Cálculos proporcionales directos (Gasto vs Inversión)
        // No dependen de las ventas, sino de la relación entre ellos para que la aguja se mueva siempre.
        $gastosPerc = 0;
        $comprasPerc = 0;
        if ($totalEgresos > 0) {
            $gastosPerc = round(($gastosMes / $totalEgresos) * 100);
            $comprasPerc = round(($comprasMes / $totalEgresos) * 100);
        }

        // Evaluación Neta: Salud del negocio basada en ingresos vs egresos totales
        $evaluacionLocal = 0;
        if ($ventasLocalHoy > 0) {
            $evaluacionLocal = min(100, round(($ventasLocalHoy / ($totalEgresos + 1)) * 50)); 
        } elseif ($totalEgresos > 0) {
            // Si hay egresos pero no hay ventas, la evaluación es lógicamente baja
            $evaluacionLocal = 5;
        }

        return view('empresa.dashboard.index', [
            'empresa' => $empresa,
            'ventasLocalHoy' => $ventasLocalHoy,
            'ventasInternetHoy' => $ventasInternetHoy,
            'gastosHoy' => $gastosMes,
            'comprasHoy' => $comprasMes,
            'ventasHoy' => $ventasLocalHoy + $ventasInternetHoy,
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
        ]);
    }

    public function novedades()
    {
        $updates = \App\Models\SystemUpdate::orderByDesc('publish_date')->get();
        return view('empresa.updates.index', compact('updates'));
    }
}
