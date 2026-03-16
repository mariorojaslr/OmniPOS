<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class ReporteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PANEL (BOTONERA)
    |--------------------------------------------------------------------------
    */
    public function panel()
    {
        return view('empresa.reportes.panel');
    }

    /*
    |--------------------------------------------------------------------------
    | RANKING PRODUCTOS
    |--------------------------------------------------------------------------
    */
    public function rankingProductos()
    {
        $rankingProductos = $this->getRankingProductos();

        return view('empresa.reportes.ranking_productos', compact(
            'rankingProductos'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | RANKING CLIENTES
    |--------------------------------------------------------------------------
    */
    public function rankingClientes()
    {
        $rankingClientes = $this->getRankingClientes();

        return view('empresa.reportes.ranking_clientes', compact(
            'rankingClientes'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VENTAS POR FECHA
    |--------------------------------------------------------------------------
    */
    public function ventasPorFecha()
    {
        $empresaId = auth()->user()->empresa_id;

        $ventas = DB::table('ventas as v')
            ->join('venta_items as vi', 'vi.venta_id', '=', 'v.id')
            ->join('products as p', 'p.id', '=', 'vi.product_id')
            ->where('v.empresa_id', $empresaId) // 🔴 FILTRO EMPRESA
            ->select(
                DB::raw('DATE(v.created_at) as fecha'),
                DB::raw('COUNT(DISTINCT v.id) as cantidad'),
                DB::raw('SUM(vi.cantidad * p.price) as total')
            )
            ->groupBy(DB::raw('DATE(v.created_at)'))
            ->orderByDesc(DB::raw('DATE(v.created_at)'))
            ->limit(30)
            ->get();

        return view('empresa.reportes.ventas_fecha', compact('ventas'));
    }

    /*
    |--------------------------------------------------------------------------
    | REPORTE GENERAL
    |--------------------------------------------------------------------------
    */
    public function empresa()
    {
        $rankingProductos = $this->getRankingProductos();
        $rankingClientes  = $this->getRankingClientes();

        return view('empresa.reportes.empresa', compact(
            'rankingProductos',
            'rankingClientes'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------------------------
    */
    public function exportPdf()
    {
        $rankingProductos = $this->getRankingProductos();
        $rankingClientes  = $this->getRankingClientes();

        $pdf = Pdf::loadView('empresa.reportes.pdf', compact(
            'rankingProductos',
            'rankingClientes'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('reporte.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */
    public function exportExcel()
    {
        $rankingProductos = $this->getRankingProductos();
        $rankingClientes  = $this->getRankingClientes();

        $data = [];

        /*
        |--------------------------
        | RANKING PRODUCTOS
        |--------------------------
        */
        $data[] = ['RANKING PRODUCTOS'];
        $data[] = ['#', 'Producto', 'Cantidad', 'Precio', 'Total'];

        foreach ($rankingProductos as $i => $item) {
            $data[] = [
                $i + 1,
                $item->producto_nombre,
                (float) $item->total,
                (float) $item->price,
                (float) ($item->total * $item->price)
            ];
        }

        /*
        |--------------------------
        | RANKING CLIENTES
        |--------------------------
        */
        $data[] = [];
        $data[] = ['RANKING CLIENTES'];
        $data[] = ['#', 'Cliente', 'Compras', 'Total Gastado', 'Promedio'];

        foreach ($rankingClientes as $i => $item) {
            $data[] = [
                $i + 1,
                $item->cliente_nombre ?? 'Consumidor final',
                (float) $item->total_compras,
                (float) $item->total_gastado,
                (float) $item->promedio_compra
            ];
        }

        if (!class_exists('Maatwebsite\Excel\Facades\Excel')) {
            return back()->with('error', 'La librería de exportación a Excel no está instalada en este entorno. Por favor, contacte el soporte técnico o use el formato PDF.');
        }

        return Excel::download(new ArrayExport($data), 'reporte.xlsx');
    }

    /*
    |--------------------------------------------------------------------------
    | CONSULTAS PRIVADAS
    |--------------------------------------------------------------------------
    */

    private function getRankingProductos()
    {
        $empresaId = auth()->user()->empresa_id;

        return DB::table('venta_items')
            ->join('ventas', 'ventas.id', '=', 'venta_items.venta_id')
            ->join('products', 'products.id', '=', 'venta_items.product_id')
            ->where('ventas.empresa_id', $empresaId) // 🔴 FILTRO EMPRESA
            ->select(
                'venta_items.product_id',
                'products.name as producto_nombre',
                'products.price',
                DB::raw('SUM(venta_items.cantidad) as total')
            )
            ->groupBy(
                'venta_items.product_id',
                'products.name',
                'products.price'
            )
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    private function getRankingClientes()
    {
        $empresaId = auth()->user()->empresa_id;

        return DB::table('ventas as v')
            ->join('venta_items as vi', 'vi.venta_id', '=', 'v.id')
            ->join('products as p', 'p.id', '=', 'vi.product_id')
            ->where('v.empresa_id', $empresaId) // 🔴 FILTRO EMPRESA
            ->select(
                'v.cliente_nombre',
                DB::raw('COUNT(DISTINCT v.id) as total_compras'),
                DB::raw('SUM(vi.cantidad * p.price) as total_gastado'),
                DB::raw('AVG(vi.cantidad * p.price) as promedio_compra')
            )
            ->groupBy('v.cliente_nombre')
            ->orderByDesc('total_gastado')
            ->limit(10)
            ->get();
    }
}
