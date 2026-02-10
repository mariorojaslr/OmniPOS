<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function empresa(Request $request)
    {
        $desde = $request->desde;
        $hasta = $request->hasta;

        /*
        |--------------------------------------------------------------------------
        | RANKING PRODUCTOS
        |--------------------------------------------------------------------------
        | Tabla: detalle_ventas
        | Campos usados:
        | - producto_nombre
        | - cantidad
        | - created_at
        */

        $rankingProductos = DB::table('detalle_ventas')
            ->select('producto_nombre', DB::raw('SUM(cantidad) as total'))
            ->when($desde && $hasta, function ($query) use ($desde, $hasta) {
                $query->whereBetween('created_at', [$desde, $hasta]);
            })
            ->groupBy('producto_nombre')
            ->orderByDesc('total')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RANKING CLIENTES
        |--------------------------------------------------------------------------
        | Tabla: ventas
        | Campos usados:
        | - cliente_nombre
        | - created_at
        */

        $rankingClientes = DB::table('ventas')
            ->select('cliente_nombre', DB::raw('COUNT(*) as total_compras'))
            ->when($desde && $hasta, function ($query) use ($desde, $hasta) {
                $query->whereBetween('created_at', [$desde, $hasta]);
            })
            ->groupBy('cliente_nombre')
            ->orderByDesc('total_compras')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL FACTURADO (opcional - ya lo dejamos preparado)
        |--------------------------------------------------------------------------
        */

        $totalFacturado = DB::table('ventas')
            ->when($desde && $hasta, function ($query) use ($desde, $hasta) {
                $query->whereBetween('created_at', [$desde, $hasta]);
            })
            ->sum('total');


        /*
        |--------------------------------------------------------------------------
        | VENTAS POR DÍA (base para gráfico futuro)
        |--------------------------------------------------------------------------
        */

        $ventasPorDia = DB::table('ventas')
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('SUM(total) as total_dia'))
            ->when($desde && $hasta, function ($query) use ($desde, $hasta) {
                $query->whereBetween('created_at', [$desde, $hasta]);
            })
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get();


        return view('reportes.empresa', compact(
            'rankingProductos',
            'rankingClientes',
            'totalFacturado',
            'ventasPorDia',
            'desde',
            'hasta'
        ));
    }
}
