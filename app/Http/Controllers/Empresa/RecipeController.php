<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * Listado de todas las recetas de la empresa
     */
    public function index()
    {
        $recipes = Recipe::where('empresa_id', auth()->user()->empresa_id)
            ->with(['product.unit', 'items'])
            ->get();
        return view('empresa.recipes.index', compact('recipes'));
    }

    /**
     * Vista para crear una receta (paso 1: elegir producto)
     */
    public function create()
    {
        $products = Product::where('empresa_id', auth()->user()->empresa_id)
            ->where('usage_type', 'sell')
            ->where('active', true)
            ->whereDoesntHave('recipe')
            ->orderBy('name')
            ->get();

        return view('empresa.recipes.create', compact('products'));
    }

    /**
     * Guardar la base de la receta
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'nullable|string|max:255',
        ]);

        $recipe = Recipe::create([
            'empresa_id' => auth()->user()->empresa_id,
            'product_id' => $request->product_id,
            'name'       => $request->name ?: 'Receta estándar',
            'is_active'  => true,
        ]);

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Creó la receta base para el producto: {$recipe->product->name}", $recipe);

        return redirect()->route('empresa.recipes.edit', $recipe)
            ->with('success', 'Receta base creada. Ahora agrega los ingredientes.');
    }

    /**
     * El EDITOR de Receta (Paso 2)
     */
    public function edit(Recipe $recipe)
    {
        if ($recipe->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $recipe->load('product', 'items.component', 'items.unit');
        
        $ingredients = Product::where('empresa_id', auth()->user()->empresa_id)
            ->whereIn('usage_type', ['raw_material', 'supply'])
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::all();

        return view('empresa.recipes.edit', compact('recipe', 'ingredients', 'units'));
    }

    /**
     * Añadir ingrediente
     */
    public function addItem(Request $request, Recipe $recipe)
    {
        if ($recipe->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $request->validate([
            'component_product_id' => 'required|exists:products,id',
            'quantity'             => 'required|numeric|min:0.0001',
            'unit_id'              => 'nullable|exists:units,id',
        ]);

        $exists = RecipeItem::where('recipe_id', $recipe->id)
            ->where('component_product_id', $request->component_product_id)
            ->first();

        if ($exists) {
            return back()->with('error', 'Este ingrediente ya está en la receta.');
        }

        $component = Product::find($request->component_product_id);

        RecipeItem::create([
            'recipe_id'            => $recipe->id,
            'component_product_id' => $request->component_product_id,
            'quantity'             => $request->quantity,
            'unit_id'              => $request->unit_id,
        ]);

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Añadió ingrediente '{$component->name}' a la receta de '{$recipe->product->name}'", $recipe);

        return back()->with('success', 'Ingrediente añadido.');
    }

    /**
     * Producir lote (Transformación de Insumos a Producto Terminado)
     */
    public function produce(Request $request, Recipe $recipe)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01'
        ]);

        if ($recipe->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $quantity = $request->quantity;

        // 1. Verificar si hay stock suficiente de todos los ingredientes
        foreach ($recipe->items as $item) {
            if (!$item->product) continue;
            
            $needed = $item->quantity * $quantity;
            if ($item->product->stock < $needed) {
                return back()->with('error', "Stock insuficiente de '{$item->product->name}'. Se necesitan {$needed} {$item->product->unit->short_name} y solo hay {$item->product->stock}.");
            }
        }

        // 2. Realizar la transformación (Transacción de Stock)
        \DB::transaction(function() use ($recipe, $quantity) {
            // Restar Insumos
            foreach ($recipe->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity * $quantity);
                }
            }

            // Sumar Producto Terminado
            if ($recipe->product) {
                $recipe->product->increment('stock', $quantity);
            }
        });

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Producción local: Fabricó {$quantity} unidades de '{$recipe->product->name}' usando receta.", $recipe);

        return back()->with('success', "Proceso de producción exitoso: Se han fabricado {$quantity} unidades de '{$recipe->product->name}'. Los insumos han sido descontados correctamente.");
    }

    /**
     * Eliminar ingrediente
     */
    public function removeItem(RecipeItem $item)
    {
        if ($item->recipe->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $targetName = $item->product->name ?? 'Ingrediente';
        $recipeName = $item->recipe->product->name ?? 'Producto';
        $recipe = $item->recipe;

        $item->delete();

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Removió ingrediente '{$targetName}' de la receta de '{$recipeName}'", $recipe);

        return back()->with('success', 'Ingrediente removido.');
    }

    /**
     * Eliminar receta
     */
    public function destroy(Recipe $recipe)
    {
        if ($recipe->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $productName = $recipe->product->name ?? 'Desconocido';
        $recipe->delete();

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Eliminó la receta completa del producto: {$productName}");

        return redirect()->route('empresa.recipes.index')->with('success', 'Receta eliminada.');
    }
}
