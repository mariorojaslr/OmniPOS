<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Mostrar catálogo público de una empresa
     * Con filtros:
     * - mas_vendidos
     * - nuevos
     * - promociones
     */
    public function index(Request $request, Empresa $empresa)
    {
        // Base: productos activos de la empresa
        $query = Product::where('empresa_id', $empresa->id)
            ->where('active', true)
            ->with('images');

        /**
         * =============================
         * FILTROS DEL CATALOGO
         * =============================
         */
        $filtro = $request->get('filtro');

        switch ($filtro) {

            /**
             * Más vendidos
             * Requiere campo: total_vendido (si no existe, luego lo agregamos bien)
             */
            case 'mas_vendidos':
                if (\Schema::hasColumn('products', 'total_vendido')) {
                    $query->orderByDesc('total_vendido');
                }
                break;

            /**
             * Nuevos productos
             */
            case 'nuevos':
                $query->orderByDesc('created_at');
                break;

            /**
             * Promociones
             * Requiere campo: en_promocion o precio_oferta (si no existe lo creamos bien)
             */
            case 'promociones':
                if (\Schema::hasColumn('products', 'en_promocion')) {
                    $query->where('en_promocion', true);
                }
                break;
        }

        $products = $query->get();

        return view('catalog.index', compact('empresa', 'products', 'filtro'));
    }

    /**
     * Mostrar producto individual
     */
    public function show(Empresa $empresa, Product $product)
    {
        // Seguridad multiempresa
        if ($product->empresa_id !== $empresa->id) {
            abort(404);
        }

        $product->load(['images', 'variants', 'videos']);

        return view('catalog.show', compact('empresa', 'product'));
    }
}
