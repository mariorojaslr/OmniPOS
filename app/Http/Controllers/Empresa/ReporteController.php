<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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
        $empresaId = auth()->user()->empresa_id;
        $mesActual = now()->month;
        $anioActual = now()->year;

        // KPI 1: Ventas del Mes
        $ventasMes = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->sum('total_con_iva');

        // KPI 2: Compras del Mes
        $comprasMes = DB::table('purchases')
            ->where('empresa_id', $empresaId)
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->sum('total');

        // KPI 3: Deuda Clientes (Morosidad)
        $deudaClientes = DB::table('client_ledgers')
            ->where('empresa_id', $empresaId)
            ->sum(DB::raw('CASE WHEN type = "debit" THEN amount ELSE -amount END'));

        // KPI 4: Deuda a Proveedores
        $deudaProveedores = DB::table('supplier_ledgers')
            ->where('empresa_id', $empresaId)
            ->sum(DB::raw('CASE WHEN type = "debit" THEN amount ELSE -amount END'));

        // Gráfica: Ventas últimos 15 días
        $ventasLast15 = DB::table('ventas')
            ->where('empresa_id', $empresaId)
            ->where('created_at', '>=', now()->subDays(15))
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('SUM(total_con_iva) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get();

        // Categorías más vendidas
        $topCategorias = DB::table('venta_items as vi')
            ->join('ventas as v', 'v.id', '=', 'vi.venta_id')
            ->join('products as p', 'p.id', '=', 'vi.product_id')
            ->leftJoin('rubros as r', 'r.id', '=', 'p.rubro_id')
            ->where('v.empresa_id', $empresaId)
            ->select(
                DB::raw('COALESCE(r.nombre, "General") as cat'), 
                DB::raw('SUM(vi.total_item_con_iva) as monto')
            )
            ->groupBy(DB::raw('COALESCE(r.nombre, "General")'))
            ->orderByDesc('monto')
            ->limit(5)
            ->get();

        return view('empresa.reportes.panel', compact(
            'ventasMes', 'comprasMes', 'deudaClientes', 'deudaProveedores', 
            'ventasLast15', 'topCategorias'
        ));
    }

    public function ventasVendedor(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $vendedores = DB::table('ventas as v')
            ->join('users as u', 'u.id', '=', 'v.user_id')
            ->where('v.empresa_id', $empresaId)
            ->select('u.name', DB::raw('COUNT(v.id) as total_ventas'), DB::raw('SUM(v.total_con_iva) as total_monto'))
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_monto')
            ->get();
        return view('empresa.reportes.vendedores', compact('vendedores'));
    }

    public function cajaDiaria(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        // Obtenemos los últimos 50 arqueos de caja con detalle del operador
        $cierres = \App\Models\CajaCierre::with('user')
            ->where('empresa_id', $empresaId)
            ->orderByDesc('fecha_apertura')
            ->limit(50)
            ->get();

        return view('empresa.reportes.caja_diaria', compact('cierres'));
    }

    public function rentabilidad(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        // Traemos los productos destinados a la VENTA
        $products = \App\Models\Product::where('empresa_id', $empresaId)
            ->where('usage_type', 'sell')
            ->where('active', true)
            ->with(['recipe.items.component'])
            ->get();

        $data = [];

        foreach ($products as $p) {
            $costoFinal = 0;

            // Lógica Smart Costing
            if ($p->recipe && $p->recipe->items->count() > 0) {
                // Si tiene receta, sumamos sus partes
                foreach ($p->recipe->items as $item) {
                    $costoFinal += ($item->quantity * ($item->component->cost ?? 0));
                }
            } else {
                // Si no tiene receta, usamos su costo base manual
                $costoFinal = $p->cost ?? 0;
            }

            // Precio Neto (Venta sin IVA)
            $precioNeto = number_format($p->price / 1.21, 2, '.', '');
            $gananciaNeta = $precioNeto - $costoFinal;
            $margenPercent = $precioNeto > 0 ? ($gananciaNeta / $precioNeto) * 100 : 0;

            $data[] = (object) [
                'id'         => $p->id,
                'nombre'     => $p->name,
                'precio_v'   => $p->price,
                'precio_n'   => $precioNeto,
                'costo'      => $costoFinal,
                'ganancia'   => $gananciaNeta,
                'margen'     => $margenPercent,
                'tiene_r'    => ($p->recipe ? true : false)
            ];
        }

        // Ordenar por menor ganancia primero para detectar problemas
        $items = collect($data)->sortBy('margen');

        return view('empresa.reportes.rentabilidad', compact('items'));
    }

    public function margenProducto(Request $request) { return $this->rentabilidad($request); }

    public function ventasCategoria(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $categorias = DB::table('venta_items as vi')
            ->join('ventas as v', 'v.id', '=', 'vi.venta_id')
            ->join('products as p', 'p.id', '=', 'vi.product_id')
            ->leftJoin('rubros as r', 'r.id', '=', 'p.rubro_id')
            ->where('v.empresa_id', $empresaId)
            ->select(DB::raw('IFNULL(r.nombre, "Sin Rubro") as cat'), DB::raw('SUM(vi.total_item_con_iva) as total'))
            ->groupBy('cat')
            ->get();
        return view('empresa.reportes.categorias', compact('categorias'));
    }

    public function clientesFrecuentes(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $clientes = DB::table('ventas as v')
            ->where('v.empresa_id', $empresaId)
            ->select('cliente_nombre', DB::raw('COUNT(id) as visitas'), DB::raw('SUM(total_con_iva) as total'))
            ->groupBy('cliente_nombre')
            ->orderByDesc('visitas')
            ->limit(20)
            ->get();
        return view('empresa.reportes.clientes_frecuentes', compact('clientes'));
    }

    public function ventasPorHora(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $horas = DB::table('ventas as v')
            ->where('v.empresa_id', $empresaId)
            ->select(DB::raw('HOUR(created_at) as hora'), DB::raw('COUNT(id) as cant'), DB::raw('SUM(total_con_iva) as total'))
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();
        return view('empresa.reportes.por_hora', compact('horas'));
    }

    public function analisisMensual(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $meses = DB::table('ventas as v')
            ->where('v.empresa_id', $empresaId)
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'), DB::raw('COUNT(id) as ventas'), DB::raw('SUM(total_con_iva) as total'))
            ->groupBy('mes')
            ->orderByDesc('mes')
            ->get();
        return view('empresa.reportes.analisis_mensual', compact('meses'));
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

        // Usamos total_con_iva de la venta directamente — refleja lo cobrado realmente
        $ventas = DB::table('ventas as v')
            ->where('v.empresa_id', $empresaId)
            ->select(
                DB::raw('DATE(v.created_at) as fecha'),
                DB::raw('COUNT(v.id) as cantidad'),
                DB::raw('SUM(v.total_con_iva) as total')
            )
            ->groupBy(DB::raw('DATE(v.created_at)'))
            ->orderByDesc(DB::raw('DATE(v.created_at)'))
            ->limit(30)
            ->get();

        return view('empresa.reportes.ventas_fecha', compact('ventas'));
    }

    /*
    |--------------------------------------------------------------------------
    | DETALLE DE VENTAS POR FECHA (AJAX)
    |--------------------------------------------------------------------------
    */
    public function ventasDetallePorFecha(Request $request)
    {
        $fecha = $request->get('fecha');
        $empresaId = auth()->user()->empresa_id;

        $ventas = \App\Models\Venta::with(['items', 'user'])
            ->where('empresa_id', $empresaId)
            ->whereDate('created_at', $fecha)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('empresa.reportes._ventas_detalle_partial', compact('ventas', 'fecha'));
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
