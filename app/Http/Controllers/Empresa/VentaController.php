<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'total' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $ventaId = DB::table('ventas')->insertGetId([
                'empresa_id' => auth()->user()->empresa_id,
                'total' => $request->total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->items as $item) {
                DB::table('venta_items')->insert([
                    'venta_id' => $ventaId,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['qty'],
                    'precio' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'ok' => true,
                'venta_id' => $ventaId
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
