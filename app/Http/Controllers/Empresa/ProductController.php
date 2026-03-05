<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE PRODUCTOS
    |--------------------------------------------------------------------------
    | • Búsqueda opcional
    | • Paginado dinámico
    | • Multiempresa seguro
    | • Soporte AJAX futuro
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $buscar  = $request->get('q');
        $perPage = (int) $request->get('per_page', 15);

        // Seguridad paginado
        if (!in_array($perPage, [10,15,25,50,100])) {
            $perPage = 15;
        }

        $query = Product::where('empresa_id', $empresaId);

        if (!empty($buscar)) {
            $query->where('name', 'like', "%{$buscar}%");
        }

        /*
        |--------------------------------------------------------------------------
        | Soporte AJAX
        |--------------------------------------------------------------------------
        */

        if ($request->ajax() || $request->get('ajax')) {

            return response()->json(
                $query->orderBy('name')
                    ->limit(50)
                    ->get(['id','name','price','active'])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Paginado
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return view('empresa.products.index', compact(
            'products',
            'buscar',
            'perPage'
        ));
    }



    /*
    |--------------------------------------------------------------------------
    | FORMULARIO CREAR
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('empresa.products.create');
    }



    /*
    |--------------------------------------------------------------------------
    | GUARDAR PRODUCTO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'descripcion_corta' => 'nullable|string',
            'descripcion_larga' => 'nullable|string',
        ]);

        $product = Product::create([
            'empresa_id'        => Auth::user()->empresa_id,
            'name'              => $request->name,
            'price'             => $request->price,
            'descripcion_corta' => $request->descripcion_corta,
            'descripcion_larga' => $request->descripcion_larga,
            'active'            => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN INTELIGENTE
        |--------------------------------------------------------------------------
        */

        if ($request->action === 'save_return') {

            if ($request->return) {
                return redirect($request->return)
                    ->with('success','Producto creado correctamente');
            }

            return redirect()->route('empresa.products.index')
                ->with('success','Producto creado correctamente');
        }

        return redirect()->route('empresa.products.edit', $product)
            ->with('success','Producto creado correctamente');
    }



    /*
    |--------------------------------------------------------------------------
    | EDITAR PRODUCTO
    |--------------------------------------------------------------------------
    */

    public function edit(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        return view('empresa.products.edit', compact('product'));
    }



    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR PRODUCTO
    |--------------------------------------------------------------------------
    | • Mantiene retorno dinámico
    | • Compatible con Inventario
    | • Compatible con Productos
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'active'            => 'required|boolean',
            'descripcion_corta' => 'nullable|string',
            'descripcion_larga' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR DATOS
        |--------------------------------------------------------------------------
        */

        $product->update([
            'name'              => $request->name,
            'price'             => $request->price,
            'active'            => $request->active,
            'descripcion_corta' => $request->descripcion_corta,
            'descripcion_larga' => $request->descripcion_larga,
        ]);


        /*
        |--------------------------------------------------------------------------
        | DETERMINAR URL DE RETORNO
        |--------------------------------------------------------------------------
        */

        $returnUrl = $request->input('return');


        /*
        |--------------------------------------------------------------------------
        | BOTÓN: GUARDAR Y VOLVER
        |--------------------------------------------------------------------------
        */

        if ($request->action === 'save_return') {

            if ($returnUrl) {
                return redirect($returnUrl)
                    ->with('success','Producto actualizado correctamente');
            }

            return redirect()->route('empresa.products.index')
                ->with('success','Producto actualizado correctamente');
        }


        /*
        |--------------------------------------------------------------------------
        | BOTÓN: GUARDAR
        | Se queda en edición
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('empresa.products.edit', $product)
            ->with('success','Producto actualizado correctamente');
    }
}
