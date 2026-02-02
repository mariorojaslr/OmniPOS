<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Product;

class CatalogController extends Controller
{
    public function index(Empresa $empresa)
    {
        // Solo productos activos de esa empresa
        $products = Product::where('empresa_id', $empresa->id)
            ->where('active', true)
            ->with('images')
            ->get();

        return view('catalog.index', compact('empresa', 'products'));
    }

    public function show(Empresa $empresa, Product $product)
    {
        // Seguridad básica: producto pertenece a la empresa
        if ($product->empresa_id !== $empresa->id) {
            abort(404);
        }

        $product->load('images');

        return view('catalog.show', compact('empresa', 'product'));
    }
}
