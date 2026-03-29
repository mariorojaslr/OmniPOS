<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\KardexMovimiento;
use App\Models\PurchaseItem;

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
            'productos' // Pasamos el paginador
        ));
    }

    /**
     * Obtener actividad reciente de un producto (AJAX para el acordeón)
     */
    public function actividad(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $movimientos = KardexMovimiento::where('product_id', $product->id)
            ->latest()
            ->take(5)
            ->get();

        $compras = PurchaseItem::where('product_id', $product->id)
            ->with('purchase.supplier')
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'movimientos' => $movimientos,
            'compras' => $compras
        ]);
    }
}
