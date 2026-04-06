<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\KardexMovimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KardexExport;

class StockController extends Controller
{
    /**
     * Reporte de Valorización de Inventario
     * (Capital Inmovilizado)
     */
    public function valuation()
    {
        $empresaId = Auth::user()->empresa_id;

        // Calculamos el valor total agrupado por tipo de uso
        $valuationData = Product::where('empresa_id', $empresaId)
            ->where('stock', '>', 0)
            ->where('cost', '>', 0)
            ->selectRaw('usage_type, count(*) as count, sum(stock * cost) as total_value')
            ->groupBy('usage_type')
            ->get();

        $totalGeneral = $valuationData->sum('total_value');
        $totalItems = $valuationData->sum('count');

        // Productos con mayor valor inmovilizado (Top 15)
        $topValuation = Product::where('empresa_id', $empresaId)
            ->where('stock', '>', 0)
            ->where('cost', '>', 0)
            ->with('unit')
            ->select('*')
            ->selectRaw('(stock * cost) as valuation')
            ->orderByDesc('valuation')
            ->limit(15)
            ->get();

        return view('empresa.stock.valuation', compact('valuationData', 'totalGeneral', 'totalItems', 'topValuation'));
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE STOCK
    |--------------------------------------------------------------------------
    | • Usa products.stock (campo real del sistema)
    | • Multiempresa
    | • Búsqueda
    | • Filtro por estado
    | • Paginado configurable
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $filas     = (int) $request->input('filas', 10);
        $busqueda  = $request->input('q');
        $estado    = $request->input('estado');

        $query = Product::where('empresa_id', $empresaId);

        if (!empty($busqueda)) {
            $query->where('name', 'like', "%{$busqueda}%");
        }

        /*
        |--------------------------------------------------------------------------
        | FILTROS POR ESTADO
        |--------------------------------------------------------------------------
        | Se usa products.stock (campo real)
        */

        if ($estado === 'critico') {
            $query->where('stock', '<=', 0);
        }

        if ($estado === 'bajo') {
            $query->where('stock', '>', 0)
                  ->whereColumn('stock', '<=', 'stock_min');
        }

        if ($estado === 'ok') {
            $query->whereColumn('stock', '>', 'stock_min');
        }

        $productos = $query
            ->orderBy('name')
            ->paginate($filas)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | CONTADORES DE ESTADO
        |--------------------------------------------------------------------------
        */

        $critico = Product::where('empresa_id', $empresaId)
            ->where('stock', '<=', 0)
            ->count();

        $bajo = Product::where('empresa_id', $empresaId)
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'stock_min')
            ->count();

        $ok = Product::where('empresa_id', $empresaId)
            ->whereColumn('stock', '>', 'stock_min')
            ->count();

        return view('empresa.stock.index', compact(
            'productos',
            'filas',
            'busqueda',
            'estado',
            'ok',
            'bajo',
            'critico'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR CONFIGURACIÓN STOCK MIN / IDEAL
    |--------------------------------------------------------------------------
    */
    public function config(Request $request, Product $product)
    {
        $request->validate([
            'minimo' => 'nullable|numeric|min:0',
            'ideal'  => 'nullable|numeric|min:0',
        ]);

        if ($product->empresa_id != Auth::user()->empresa_id) {
            abort(403);
        }

        $product->stock_min   = $request->minimo ?? 0;
        $product->stock_ideal = $request->ideal ?? 0;
        $product->save();

        return back()->with('ok', 'Configuración de stock guardada');
    }


    /*
    |--------------------------------------------------------------------------
    | KARDEX
    |--------------------------------------------------------------------------
    */
    public function kardex(Request $request, Product $product)
    {
        if ($product->empresa_id != Auth::user()->empresa_id) {
            abort(403);
        }

        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = KardexMovimiento::where('empresa_id', Auth::user()->empresa_id)
            ->where('product_id', $product->id);

        if ($desde) {
            $query->whereDate('created_at', '>=', $desde);
        }

        if ($hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        $movimientos = $query
            ->orderBy('created_at')
            ->paginate(30)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | DATOS PARA GRÁFICO
        |--------------------------------------------------------------------------
        */

        $graficoFechas = $movimientos->getCollection()
            ->pluck('created_at')
            ->map(fn($d) => $d->format('d/m H:i'));

        $graficoStock = $movimientos->getCollection()
            ->pluck('stock_resultante');

        /*
        |--------------------------------------------------------------------------
        | ALERTA DE STOCK
        |--------------------------------------------------------------------------
        */

        $alertaMinimo = $product->stock <= $product->stock_min;

        return view('empresa.stock.kardex', compact(
            'product',
            'movimientos',
            'graficoFechas',
            'graficoStock',
            'desde',
            'hasta',
            'alertaMinimo'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR KARDEX PDF
    |--------------------------------------------------------------------------
    */
    public function exportPdf(Request $request, Product $product)
    {
        if ($product->empresa_id != Auth::user()->empresa_id) {
            abort(403);
        }

        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = KardexMovimiento::where('empresa_id', Auth::user()->empresa_id)
            ->where('product_id', $product->id);

        if ($desde) {
            $query->whereDate('created_at', '>=', $desde);
        }

        if ($hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        $movimientos = $query->orderBy('created_at')->get();

        $pdf = Pdf::loadView('empresa.stock.kardex_pdf', compact(
            'product',
            'movimientos',
            'desde',
            'hasta'
        ));

        return $pdf->download('kardex_'.$product->id.'.pdf');
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR KARDEX EXCEL
    |--------------------------------------------------------------------------
    */
    public function exportExcel(Request $request, Product $product)
    {
        if ($product->empresa_id != Auth::user()->empresa_id) {
            abort(403);
        }

        return Excel::download(
            new KardexExport(
                $product->id,
                $request->input('desde'),
                $request->input('hasta')
            ),
            'kardex_'.$product->id.'.xlsx'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MOVIMIENTO MANUAL DE STOCK
    |--------------------------------------------------------------------------
    | Tipos:
    | • entrada
    | • salida
    | • ajuste
    |--------------------------------------------------------------------------
    */
    public function movimientoManual(Request $request, Product $product)
    {
        $request->validate([
            'tipo'     => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|numeric|min:0.01',
            'origen'   => 'nullable|string|max:255',
        ]);

        if ($product->empresa_id != Auth::user()->empresa_id) {
            abort(403);
        }

        $cantidad = (float) $request->cantidad;

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR STOCK REAL
        |--------------------------------------------------------------------------
        */

        if ($request->tipo === 'entrada') {
            $product->stock += $cantidad;
        }
        elseif ($request->tipo === 'salida') {
            $product->stock -= $cantidad;
        }
        else {
            $product->stock = $cantidad;
        }

        $product->save();

        /*
        |--------------------------------------------------------------------------
        | REGISTRAR KARDEX
        |--------------------------------------------------------------------------
        */

        KardexMovimiento::create([
            'empresa_id'       => Auth::user()->empresa_id,
            'product_id'       => $product->id,
            'user_id'          => Auth::id(),
            'tipo'             => $request->tipo,
            'cantidad'         => $cantidad,
            'stock_resultante' => $product->stock,
            'origen'           => $request->origen ?? 'Movimiento manual',
        ]);

        return back()->with('ok', 'Movimiento de stock registrado correctamente');
    }

}
