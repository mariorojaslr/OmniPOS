<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $products = Product::where('empresa_id', $empresaId)
            ->orderBy('name')
            ->get();

        return view('empresa.products.index', compact('products'));
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

        return redirect()
            ->route('empresa.products.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        return view('empresa.products.edit', compact('product'));
    }

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

    private function authorizeProduct(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }
    }
}
