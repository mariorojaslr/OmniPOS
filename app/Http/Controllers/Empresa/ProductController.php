<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * =========================================================
     * LISTADO DE PRODUCTOS
     * =========================================================
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $buscar = $request->q;

        $query = Product::where('empresa_id', $empresaId);

        if (!empty($buscar)) {
            $query->where('name', 'like', "%{$buscar}%");
        }

        /*
        |---------------------------------------------------------
        | BUSQUEDA AJAX (para buscadores en vivo)
        |---------------------------------------------------------
        */
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json(
                $query->orderBy('name')
                      ->limit(50)
                      ->get(['id','name','price','active'])
            );
        }

        /*
        |---------------------------------------------------------
        | LISTADO PAGINADO NORMAL
        |---------------------------------------------------------
        */
        $products = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('empresa.products.index', compact('products', 'buscar'));
    }


    /**
     * =========================================================
     * FORMULARIO CREAR PRODUCTO
     * =========================================================
     */
    public function create()
    {
        return view('empresa.products.create');
    }


    /**
     * =========================================================
     * GUARDAR PRODUCTO
     * =========================================================
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

        /*
        |---------------------------------------------------------
        | VOLVER AL LUGAR DE ORIGEN
        |---------------------------------------------------------
        */
        return redirect()->back()
            ->with('success', 'Producto creado correctamente');
    }


    /**
     * =========================================================
     * EDITAR PRODUCTO
     * =========================================================
     */
    public function edit(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        return view('empresa.products.edit', compact('product'));
    }


    /**
     * =========================================================
     * ACTUALIZAR PRODUCTO
     * =========================================================
     */
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

        /*
        |---------------------------------------------------------
        | VOLVER AL LUGAR DE ORIGEN (Inventario o Productos)
        |---------------------------------------------------------
        */
        return redirect()->back()
            ->with('success', 'Producto actualizado');
    }
}
