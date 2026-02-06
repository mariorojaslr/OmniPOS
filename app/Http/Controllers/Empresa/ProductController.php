<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Listado de productos con paginado
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        // Consulta base
        $query = Product::where('empresa_id', $empresaId)
            ->orderBy('name');

        // 🔍 Buscador opcional por nombre (si existe input q)
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // ⚠️ IMPORTANTE: paginate (NO get)
        $products = $query->paginate(15)->withQueryString();

        return view('empresa.products.index', compact('products'));
    }

    /**
     * Formulario crear producto
     */
    public function create()
    {
        return view('empresa.products.create');
    }

    /**
     * Guardar producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create([
            'empresa_id' => Auth::user()->empresa_id,
            'name'       => $request->name,
            'price'      => $request->price,
            'active'     => true,
        ]);

        return redirect()
            ->route('empresa.products.index')
            ->with('success', 'Producto creado correctamente');
    }

    /**
     * Formulario editar producto
     */
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        return view('empresa.products.edit', compact('product'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'name'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'active' => 'required|boolean',
        ]);

        $product->update($request->only('name', 'price', 'active'));

        return redirect()
            ->route('empresa.products.index')
            ->with('success', 'Producto actualizado');
    }

    /**
     * Seguridad: evita que una empresa edite productos de otra
     */
    private function authorizeProduct(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }
    }
}
