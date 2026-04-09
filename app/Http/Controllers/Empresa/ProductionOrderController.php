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
            ->with(['recipe.product.unit', 'user'])
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
        $maxPossible = 999999;

        foreach ($recipe->items as $item) {
            if (!$item->component) continue;
            
            $needed = $item->quantity * $quantity;
            $available = $item->component->stock;
            $shortage = max(0, $needed - $available);
            
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
            ProductionOrder::create([
                'empresa_id'   => auth()->user()->empresa_id,
                'user_id'      => auth()->id(),
                'recipe_id'    => $recipe->id,
                'quantity'     => $request->quantity,
                'status'       => 'completada',
                'completed_at' => now(),
                'notes'        => $request->notes
            ]);

            foreach ($recipe->items as $item) {
                if ($item->component) {
                    $item->component->decrement('stock', $item->quantity * $request->quantity);
                }
            }

            if ($recipe->product) {
                $recipe->product->increment('stock', $request->quantity);
            }
        });

        DB::commit(); // No estaba explícito pero el final del closure lo hace, lo pongo para claridad si fuera necesario.
        
        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Fabricó un lote de producción: {$request->quantity} unidades de '{$recipe->product->name}'", $recipe);

        return redirect()->route('empresa.production_orders.index')
            ->with('success', "¡Lote de producción completado! Se han fabricado {$request->quantity} unidades y restado sus insumos.");
    }

    /**
     * Vista de detalle de la orden
     */
    public function show(ProductionOrder $production_order)
    {
        if ($production_order->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $production_order->load(['recipe.product.unit', 'recipe.items.component.unit', 'user']);

        return view('empresa.production_orders.show', compact('production_order'));
    }

    /**
     * Formulario de edición (estado + notas)
     */
    public function edit(ProductionOrder $production_order)
    {
        if ($production_order->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $production_order->load(['recipe.product.unit', 'user']);

        return view('empresa.production_orders.edit', compact('production_order'));
    }

    /**
     * Guardar cambios de estado y notas
     */
    public function update(Request $request, ProductionOrder $production_order)
    {
        if ($production_order->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pendiente,completada,cancelada',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $production_order->update([
            'status' => $request->status,
            'notes'  => $request->notes,
            'completed_at' => ($request->status === 'completada' && !$production_order->completed_at) ? now() : $production_order->completed_at,
        ]);

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Actualizó el estado de la orden de producción #{$production_order->id} a '{$request->status}'", $production_order->recipe);

        return redirect()->route('empresa.production_orders.show', $production_order)
            ->with('success', 'Orden de producción actualizada correctamente.');
    }

    /**
     * Clonar una orden (pre-rellenar el formulario de creación con sus datos)
     */
    public function clone(ProductionOrder $production_order)
    {
        if ($production_order->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Inició la clonación de la orden de producción #{$production_order->id}", $production_order->recipe);

        return redirect()->route('empresa.production_orders.create', [
            'recipe_id' => $production_order->recipe_id,
            'quantity'  => $production_order->quantity,
        ])->with('info', "Orden clonada. Revise los insumos disponibles y confirme el nuevo lote.");
    }
}
