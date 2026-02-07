<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * LISTADO DE PRODUCTOS
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $buscar = $request->q;

        $query = Product::where('empresa_id', $empresaId);

        if (!empty($buscar)) {
            $query->where('name', 'like', "%{$buscar}%");
        }

        // Si es búsqueda en vivo (AJAX) → devolver JSON
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json(
                $query->orderBy('name')
                      ->limit(50)
                      ->get(['id','name','price','active'])
            );
        }

        $products = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('empresa.products.index', compact('products', 'buscar'));
    }


    public function create()
    {
        return view('empresa.products.create');
    }


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

        return redirect()->route('empresa.products.index')
            ->with('success', 'Producto creado correctamente');
    }


    public function edit(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        return view('empresa.products.edit', compact('product'));
    }


    public function update(Request $request, Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $request->validate([
            'name'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'active' => 'required|boolean',
        ]);

        $product->update($request->only('name', 'price', 'active'));

        return redirect()->route('empresa.products.index')
            ->with('success', 'Producto actualizado');
    }
}
