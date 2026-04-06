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
     * Listado de todas las recetas
     */
    public function index()
    {
        $recipes = Recipe::with('product.unit', 'items')->get();
        return view('empresa.recipes.index', compact('recipes'));
    }

    /**
     * Vista para crear una receta (paso 1: elegir producto)
     */
    public function create()
    {
        // Solo productos destinados a la VENTA pueden tener receta
        $products = Product::where('usage_type', 'sell')
            ->where('active', true)
            ->whereDoesntHave('recipe') // Evitamos duplicados
            ->orderBy('name')
            ->get();

        return view('empresa.recipes.create', compact('products'));
    }

    /**
     * Guardar la base de la receta e ir al editor de items
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

        return redirect()->route('empresa.recipes.edit', $recipe)
            ->with('success', 'Receta base creada. Ahora agrega los ingredientes.');
    }

    /**
     * El EDITOR de Receta (Paso 2: Agregar Ingredientes)
     */
    public function edit(Recipe $recipe)
    {
        $recipe->load('product', 'items.component', 'items.unit');
        
        // Ingredientes posibles (Materias Primas o Insumos)
        $ingredients = Product::whereIn('usage_type', ['raw_material', 'supply'])
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::all();

        return view('empresa.recipes.edit', compact('recipe', 'ingredients', 'units'));
    }

    /**
     * Añadir un item a la receta
     */
    public function addItem(Request $request, Recipe $recipe)
    {
        $request->validate([
            'component_product_id' => 'required|exists:products,id',
            'quantity'             => 'required|numeric|min:0.0001',
            'unit_id'              => 'nullable|exists:units,id',
        ]);

        // Evitar duplicados del mismo ingrediente en la misma receta
        $exists = RecipeItem::where('recipe_id', $recipe->id)
            ->where('component_product_id', $request->component_product_id)
            ->first();

        if ($exists) {
            return back()->with('error', 'Este ingrediente ya está en la receta. Edita su cantidad.');
        }

        RecipeItem::create([
            'recipe_id'            => $recipe->id,
            'component_product_id' => $request->component_product_id,
            'quantity'             => $request->quantity,
            'unit_id'              => $request->unit_id,
        ]);

        return back()->with('success', 'Ingrediente añadido.');
    }

    /**
     * Eliminar un item de la receta
     */
    public function removeItem(RecipeItem $item)
    {
        $item->delete();
        return back()->with('success', 'Ingrediente removido.');
    }

    /**
     * Eliminar la receta completa
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('empresa.recipes.index')->with('success', 'Receta eliminada.');
    }
}
