<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\KardexMovimiento;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplenishmentController extends Controller
{
    /**
     * Centro de Reposición Inteligente
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $q = $request->input('q');
        $perPage = $request->input('filas', 20);

        // 1. Obtener productos con bajo stock con filtros
        $query = Product::where('empresa_id', $empresaId)
            ->where(function($sub) {
                $sub->whereColumn('stock', '<=', 'stock_min')
                  ->orWhere('stock', '<=', 0);
            });

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('barcode', 'LIKE', "%{$q}%");
            });
        }

        $productos = $query->with(['supplier', 'rubro'])
            ->orderBy('name')
            ->paginate($perPage);

        // 2. Agrupar el set actual por proveedor (solo los de la página actual)
        $porProveedor = $productos->groupBy(function($p) {
            return $p->supplier_id ?? 'sin_proveedor';
        });

        // 3. Obtener info de proveedores para los grupos
        $proveedores = Supplier::where('empresa_id', $empresaId)
            ->whereIn('id', $porProveedor->keys())
            ->get()
            ->keyBy('id');

        // 4. Estadísticas rápidas (totales globales)
        $totalFaltantes = Product::where('empresa_id', $empresaId)
            ->where(function($sub) {
                $sub->whereColumn('stock', '<=', 'stock_min')
                  ->orWhere('stock', '<=', 0);
            })->count();

        return view('empresa.stock.replenishment', compact(
            'porProveedor',
            'proveedores',
            'totalFaltantes',
            'productos'
        ));
    }

    /**
     * Exportar lista de faltantes a CSV para productividad
     */
    public function export(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $q = $request->input('q');

        $query = Product::where('empresa_id', $empresaId)
            ->where(function($sub) {
                $sub->whereColumn('stock', '<=', 'stock_min')
                  ->orWhere('stock', '<=', 0);
            });

        if ($q) {
            $query->where('name', 'LIKE', "%{$q}%");
        }

        $productos = $query->with(['supplier', 'rubro'])->orderBy('name')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=faltantes_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Producto', 'Proveedor', 'Rubro', 'Stock Actual', 'Stock Minimo', 'Stock Ideal', 'Sugerido'];

        $callback = function() use($productos, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');

            foreach ($productos as $p) {
                $sugerido = max(0, $p->stock_ideal - $p->stock);
                fputcsv($file, [
                    $p->name,
                    $p->supplier?->name ?? 'Sin Proveedor',
                    $p->rubro?->nombre ?? 'General',
                    $p->stock,
                    $p->stock_min,
                    $p->stock_ideal,
                    $p->stock_ideal > 0 ? $sugerido : 'Revisar'
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtener actividad (Kardex y Compras) vía AJAX para el acordeón
     */
    public function actividad(Product $product)
    {
        // Verificar que el producto sea de la empresa
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Últimos 10 movimientos de stock
        $movimientos = KardexMovimiento::where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Últimas 5 compras de este producto
        $compras = PurchaseItem::where('product_id', $product->id)
            ->with(['purchase.supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'movimientos' => $movimientos,
            'compras' => $compras
        ]);
    }
}
