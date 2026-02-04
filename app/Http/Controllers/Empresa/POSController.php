<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Empresa\VentaController;
use App\Models\Product;
use Illuminate\Http\Request;

class POSController extends Controller
{
    /**
     * Pantalla principal del POS
     */
    public function index()
    {
        $products = Product::with('images')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        $productsData = $products->map(function ($p) {
            return [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => (float) $p->price,
                'img'   => $p->images->first()
                    ? asset('storage/' . $p->images->first()->path)
                    : 'https://via.placeholder.com/300',
            ];
        })->values();

        return view('empresa.pos.index', [
            'products'     => $products,
            'productsData' => $productsData,
        ]);
    }

    /**
     * Checkout REAL
     * Delegamos la venta al VentaController
     */
    public function checkout(Request $request)
    {
        /**
         * Adaptamos el payload del POS
         * al formato que espera VentaController
         */
        $ventaRequest = new Request([
            'items' => collect($request->items)->map(function ($item) {
                return [
                    'product_id' => $item['id'],
                    'cantidad'   => $item['quantity'] ?? 1,
                ];
            })->toArray(),
        ]);

        /**
         * Llamamos al controller REAL de ventas
         */
        return app(VentaController::class)->store($ventaRequest);
    }
}
