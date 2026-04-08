<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\Recipe;
use App\Models\Product;
use DB;

class ProductionOrderController extends Controller
{
    /**
     * Listado de órdenes de producción
     */
    public function index()
    {
        $orders = ProductionOrder::where('empresa_id', auth()->user()->empresa_id)
            ->with(['recipe.product', 'user'])
            ->latest()
            ->paginate(15);
            
        return view('empresa.production_orders.index', compact('orders'));
    }

    /**
     * Formulario de nueva orden con Simulador
     */
    public function create(Request $request)
    {
        $recipes = Recipe::where('empresa_id', auth()->user()->empresa_id)
            ->with('product')
            ->get();
            
        $selectedRecipe = null;
        $simulation = null;
        $quantity = $request->quantity ?: 1;

        if ($request->recipe_id) {
            $selectedRecipe = Recipe::where('id', $request->recipe_id)
                ->where('empresa_id', auth()->user()->empresa_id)
                ->with('items.component.unit')
                ->first();
                
            if ($selectedRecipe) {
                // ANALIZAR FACTIBILIDAD (EXPLOSIÓN DE MATERIALES)
                $simulation = $this->analyzeFeasibility($selectedRecipe, $quantity);
            }
        }

        return view('empresa.production_orders.create', compact('recipes', 'selectedRecipe', 'simulation', 'quantity'));
    }

    /**
     * Analizar qué falta y cuánto se puede fabricar realmente
     */
    private function analyzeFeasibility($recipe, $quantity)
    {
        $items = [];
        $maxPossible = 999999; // Empezamos con un número alto e iremos bajando al mínimo limitante

        foreach ($recipe->items as $item) {
            if (!$item->component) continue;
            
            $needed = $item->quantity * $quantity;
            $available = $item->component->stock;
            $shortage = max(0, $needed - $available);
            
            // Calcular cuánto se puede fabricar con el stock de ESTE ingrediente específicamente
            $possibleWithThis = $item->quantity > 0 ? floor($available / $item->quantity) : 999999;
            $maxPossible = min($maxPossible, $possibleWithThis);
            
            $items[] = [
                'product'   => $item->component->name,
                'unit'      => $item->component->unit->short_name ?? 'U',
                'needed'    => $needed,
                'available' => $available,
                'shortage'  => $shortage,
                'status'    => ($shortage > 0) ? 'Faltante' : 'OK'
            ];
        }

        return [
            'items'        => $items,
            'max_possible' => $maxPossible,
            'can_produce'  => ($maxPossible >= $quantity)
        ];
    }

    /**
     * Guardar y ejecutar la transformación
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'quantity'  => 'required|numeric|min:0.01'
        ]);

        $recipe = Recipe::where('id', $request->recipe_id)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->firstOrFail();

        $feasibility = $this->analyzeFeasibility($recipe, $request->quantity);

        if (!$feasibility['can_produce'] && !$request->force) {
            return back()->with('error', "No se puede producir la cantidad solicitada. El máximo posible es " . $feasibility['max_possible'] . ".");
        }

        DB::transaction(function() use ($recipe, $request) {
            // 1. Crear el registro de la orden
            ProductionOrder::create([
                'empresa_id'   => auth()->user()->empresa_id,
                'user_id'      => auth()->id(),
                'recipe_id'    => $recipe->id,
                'quantity'     => $request->quantity,
                'status'       => 'completada',
                'completed_at' => now(),
                'notes'        => $request->notes
            ]);

            // 2. Ejecutar la Transformación de Stock
            foreach ($recipe->items as $item) {
                if ($item->component) {
                    $item->component->decrement('stock', $item->quantity * $request->quantity);
                }
            }

            if ($recipe->product) {
                $recipe->product->increment('stock', $request->quantity);
            }
        });

        return redirect()->route('empresa.production_orders.index')
            ->with('success', "¡Lote de producción completado! Se han fabricado {$request->quantity} unidades y restado sus insumos.");
    }
}
