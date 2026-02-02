<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class POSController extends Controller
{
    /**
     * Pantalla principal del POS
     */
    public function index()
    {
        // Traemos los productos activos de la empresa logueada
        $products = Product::with('images')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        /**
         * Preparamos los datos EXACTAMENTE
         * como el frontend los necesita.
         *
         * Esto evita errores de JSON, parseo,
         * imágenes rotas o pantalla vacía.
         */
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
            'products'     => $products,      // por si lo necesitás más adelante
            'productsData' => $productsData,  // ESTE es el que usa el JS
        ]);
    }

    /**
     * Checkout (por ahora SIMULADO)
     * Después lo conectamos con:
     * - Modal
     * - Métodos de pago
     * - Guardado de venta
     */
    public function checkout(Request $request)
    {
        return response()->json([
            'ok' => true,
            'message' => 'Checkout simulado correctamente'
        ]);
    }
}
