<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductCombo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rubro;

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
        $rubros = Rubro::orderBy('nombre')->get();
        return view('empresa.products.create', compact('rubros'));
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
            'barcode'           => 'nullable|string|max:100',
            'descripcion_corta' => 'nullable|string',
            'descripcion_larga' => 'nullable|string',
        ]);

        $product = Product::create([
            'empresa_id'        => Auth::user()->empresa_id,
            'name'              => $request->name,
            'price'             => $request->price,
            'barcode'           => $request->barcode,
            'rubro_id'          => $request->rubro_id,
            'active'            => true,
            'descripcion_corta' => $request->descripcion_corta,
            'descripcion_larga' => $request->descripcion_larga,
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

        $product->load(['variants', 'comboItems']);

        $rubros = Rubro::orderBy('nombre')->get();

        // Todos los productos de la empresa (para armar combos)
        $allProducts = Product::where('empresa_id', Auth::user()->empresa_id)
            ->where('id', '!=', $product->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('empresa.products.edit', compact('product', 'allProducts', 'rubros'));
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
            'barcode'           => 'nullable|string|max:100',
            'descripcion_corta' => 'nullable|string',
            'descripcion_larga' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR DATOS
        |--------------------------------------------------------------------------
        */

        $productType   = $request->input('product_type', 'normal');
        $hasVariants   = $productType === 'variants';
        $isCombo       = $productType === 'combo';

        $product->update([
            'name'              => $request->name,
            'price'             => $request->price,
            'barcode'           => $request->barcode,
            'rubro_id'          => $request->rubro_id,
            'active'            => $request->active,
            'descripcion_corta' => $request->descripcion_corta,
            'descripcion_larga' => $request->descripcion_larga,
            'has_variants'      => $hasVariants,
            'is_combo'          => $isCombo,
        ]);

        // ===== GUARDAR VARIANTES =====
        if ($hasVariants && $request->filled('variantes')) {
            foreach ($request->variantes as $key => $data) {
                if (str_starts_with((string)$key, 'new_')) {
                    // Nueva variante
                    if (!empty($data['size']) || !empty($data['color'])) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size'       => $data['size'] ?? null,
                            'color'      => $data['color'] ?? null,
                            'barcode'    => $data['barcode'] ?? null,
                            'price'      => $data['price'] ?? $product->price,
                            'stock'      => $data['stock'] ?? 0,
                        ]);
                    }
                } else {
                    // Actualizar existente
                    ProductVariant::where('id', $key)
                        ->where('product_id', $product->id)
                        ->update([
                            'size'    => $data['size'] ?? null,
                            'color'   => $data['color'] ?? null,
                            'barcode' => $data['barcode'] ?? null,
                            'price'   => $data['price'] ?? $product->price,
                            'stock'   => $data['stock'] ?? 0,
                        ]);
                }
            }

            // ✅ Actualizar stock del producto padre = suma de todas las variantes
            $stockTotal = ProductVariant::where('product_id', $product->id)->sum('stock');
            $product->update(['stock' => $stockTotal]);

        } elseif (!$hasVariants) {
            // Si ya no es de variantes, limpiar
            ProductVariant::where('product_id', $product->id)->delete();
        }

        // ===== GUARDAR COMBO =====
        if ($isCombo && $request->filled('combo_items')) {
            // Limpiar combo actual y recrear
            ProductCombo::where('parent_product_id', $product->id)->delete();
            foreach ($request->combo_items as $data) {
                if (!empty($data['child_id'])) {
                    ProductCombo::create([
                        'parent_product_id' => $product->id,
                        'child_product_id'  => $data['child_id'],
                        'quantity'          => $data['quantity'] ?? 1,
                    ]);
                }
            }
        } elseif (!$isCombo) {
            ProductCombo::where('parent_product_id', $product->id)->delete();
        }


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

    /*
    |--------------------------------------------------------------------------
    | EXPORTAR PRODUCTOS (CSV con ;)
    |--------------------------------------------------------------------------
    */
    public function export()
    {
        $empresaId = Auth::user()->empresa_id;
        $products = Product::where('empresa_id', $empresaId)->get();

        $filename = "productos_empresa_{$empresaId}_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // BOM para Excel (UTF-8)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($file, ['ID', 'Nombre', 'Precio', 'Stock Actual', 'Activo (1/0)'], ';');

            foreach ($products as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->name,
                    $p->price,
                    $p->stock,
                    $p->active ? 1 : 0
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORTAR PRODUCTOS (CSV con ;)
    |--------------------------------------------------------------------------
    */
    public function import(Request $request)
    {
        $request->validate(['csv_file' => 'required|file']);
        $empresaId = Auth::user()->empresa_id;

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Omitir BOM si existe
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($handle);

        // Omitir cabecera
        fgetcsv($handle, 1000, ';');

        $countCreated = 0;
        $countUpdated = 0;

        while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
            if (count($row) < 2) continue;

            $id     = !empty($row[0]) ? $row[0] : null;
            $nombre = trim($row[1]);
            $precio = (float) str_replace(',', '.', $row[2] ?? 0);
            $stock  = (float) ($row[3] ?? 0);
            $active = (int) ($row[4] ?? 1);

            // Buscar por ID o por Nombre (para evitar duplicados)
            $product = null;
            if ($id) {
                $product = Product::where('empresa_id', $empresaId)->find($id);
            }
            
            if (!$product) {
                $product = Product::where('empresa_id', $empresaId)
                    ->where('name', $nombre)
                    ->first();
            }

            if ($product) {
                $product->update([
                    'price'  => $precio,
                    'stock'  => $stock,
                    'active' => $active
                ]);
                $countUpdated++;
            } else {
                Product::create([
                    'empresa_id' => $empresaId,
                    'name'   => $nombre,
                    'price'  => $precio,
                    'stock'  => $stock,
                    'active' => $active
                ]);
                $countCreated++;
            }
        }

        fclose($handle);

        return back()->with('success', "Proceso terminado: {$countCreated} creados, {$countUpdated} actualizados.");
    }
}
