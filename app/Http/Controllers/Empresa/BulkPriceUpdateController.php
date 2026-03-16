<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rubro;
use Illuminate\Support\Facades\DB;

class BulkPriceUpdateController extends Controller
{
    public function index()
    {
        $rubros = Rubro::orderBy('nombre')->get();
        return view('empresa.productos.bulk-price-update', compact('rubros'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'update_type' => 'required|in:percentage,fixed',
            'amount' => 'required|numeric',
            'operation' => 'required|in:increase,decrease',
            'rubro_id' => 'nullable|exists:rubros,id',
            'rubro_from_id' => 'nullable|exists:rubros,id',
            'rubro_to_id' => 'nullable|exists:rubros,id',
        ]);

        $query = Product::query();

        // Filtro por Rubro individual
        if ($request->rubro_id) {
            $query->where('rubro_id', $request->rubro_id);
        }

        // Filtro por Rango de Rubros (basado en IDs o nombres)
        if ($request->rubro_from_id && $request->rubro_to_id) {
            $from = Rubro::find($request->rubro_from_id);
            $to = Rubro::find($request->rubro_to_id);
            
            if ($from && $to) {
                $rubroIds = Rubro::whereBetween('nombre', [$from->nombre, $to->nombre])
                    ->pluck('id');
                $query->whereIn('rubro_id', $rubroIds);
            }
        }

        $amount = (float) $request->amount;
        if ($request->operation === 'decrease') {
            $amount = -$amount;
        }

        $products = $query->get();
        $updatedCount = 0;

        DB::transaction(function () use ($products, $request, $amount, &$updatedCount) {
            foreach ($products as $product) {
                $oldPrice = $product->price;
                
                if ($request->update_type === 'percentage') {
                    $newPrice = $oldPrice * (1 + ($amount / 100));
                } else {
                    $newPrice = $oldPrice + $amount;
                }

                $product->update(['price' => max(0, $newPrice)]);
                $updatedCount++;
            }
        });

        return back()->with('success', "Se han actualizado los precios de {$updatedCount} productos correctamente.");
    }
}
