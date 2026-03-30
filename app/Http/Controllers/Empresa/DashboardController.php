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

        return view('empresa.dashboard.index', [
            'empresa' => $empresa,
            'ventasHoy' => $ventasHoy,
            'ventasMes' => $ventasMes,
            'cantidadVentasHoy' => $cantidadVentasHoy,
            'usuariosCount' => $usuariosCount,
            'clientesCount' => $clientesCount,
            'productosCount' => $productosCount,
            'stockBajo' => $stockBajo,
            'pedidosPendientes' => $pedidosPendientes,
            'pedidosTotales' => $pedidosTotales,
            'reminders' => $reminders,
        ]);
    }

    public function novedades()
    {
        $updates = \App\Models\SystemUpdate::orderByDesc('publish_date')->get();
        return view('empresa.updates.index', compact('updates'));
    }
}
