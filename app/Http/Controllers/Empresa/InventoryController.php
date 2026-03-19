<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Vista principal del escáner de inventario
     */
    public function index()
    {
        return view('empresa.inventory.scan');
    }

    /**
     * Ajustar stock vía AJAX al escanear
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'barcode'  => 'required|string',
            'mode'     => 'required|in:sum,set',
            'quantity' => 'required|numeric',
        ]);

        $empresaId = Auth::user()->empresa_id;
        $barcode   = $request->barcode;
        $mode      = $request->mode; // 'sum' para sumar +N, 'set' para establecer N
        $qty       = $request->quantity;

        // 1. Buscar en variantes
        $variant = ProductVariant::where('barcode', $barcode)
            ->whereHas('product', function($q) use ($empresaId) {
                $q->where('empresa_id', $empresaId);
            })->first();

        if ($variant) {
            if ($mode === 'sum') {
                $variant->aumentarStock($qty, 'AJUSTE ESCÁNER');
            } else {
                $variant->ajustarStock($qty, 'AJUSTE ESCÁNER');
            }

            return response()->json([
                'ok'      => true,
                'name'    => $variant->product->name . " ({$variant->size}/{$variant->color})",
                'stock'   => $variant->stock,
                'message' => 'Stock actualizado correctamente'
            ]);
        }

        // 2. Buscar en productos básicos
        $product = Product::where('empresa_id', $empresaId)
            ->where('barcode', $barcode)
            ->first();

        if ($product) {
            if ($mode === 'sum') {
                $product->aumentarStock($qty, 'AJUSTE ESCÁNER');
            } else {
                $product->ajustarStock($qty, 'AJUSTE ESCÁNER');
            }

            return response()->json([
                'ok'      => true,
                'name'    => $product->name,
                'stock'   => $product->stock,
                'message' => 'Stock actualizado correctamente'
            ]);
        }

        return response()->json([
            'ok'      => false,
            'message' => 'Producto no encontrado con el código: ' . $barcode
        ], 404);
    }
}
