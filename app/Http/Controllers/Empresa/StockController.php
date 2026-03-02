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

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE STOCK
    |--------------------------------------------------------------------------
    | • Usa stock_actual (UNIFICADO)
    | • Multiempresa
    | • Búsqueda real
    | • Filtro por estado
    | • Paginado dinámico
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

        // FILTROS POR ESTADO USANDO stock_actual
        if ($estado === 'critico') {
            $query->where('stock_actual', '<=', 0);
        }

        if ($estado === 'bajo') {
            $query->where('stock_actual', '>', 0)
                  ->whereColumn('stock_actual', '<=', 'stock_min');
        }

        if ($estado === 'ok') {
            $query->whereColumn('stock_actual', '>', 'stock_min');
        }

        $productos = $query
            ->orderBy('name')
            ->paginate($filas)
            ->withQueryString();

        // CONTADORES GLOBALES
        $critico = Product::where('empresa_id', $empresaId)
            ->where('stock_actual', '<=', 0)
            ->count();

        $bajo = Product::where('empresa_id', $empresaId)
            ->where('stock_actual', '>', 0)
            ->whereColumn('stock_actual', '<=', 'stock_min')
            ->count();

        $ok = Product::where('empresa_id', $empresaId)
            ->whereColumn('stock_actual', '>', 'stock_min')
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

        $graficoFechas = $movimientos->getCollection()
            ->pluck('created_at')
            ->map(fn($d) => $d->format('d/m H:i'));

        $graficoStock = $movimientos->getCollection()
            ->pluck('stock_resultante');

        $alertaMinimo = $product->stock_actual <= $product->stock_min;

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
    | MOVIMIENTO MANUAL DE STOCK (UNIFICADO)
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

        if ($request->tipo === 'entrada') {
            $product->stock_actual += $cantidad;
        }
        elseif ($request->tipo === 'salida') {
            $product->stock_actual -= $cantidad;
        }
        else {
            $product->stock_actual = $cantidad;
        }

        $product->save();

        KardexMovimiento::create([
            'empresa_id'       => Auth::user()->empresa_id,
            'product_id'       => $product->id,
            'user_id'          => Auth::id(),
            'tipo'             => $request->tipo,
            'cantidad'         => $cantidad,
            'stock_resultante' => $product->stock_actual,
            'origen'           => $request->origen ?? 'Movimiento manual',
        ]);

        return back()->with('ok', 'Movimiento de stock registrado correctamente');
    }
}
