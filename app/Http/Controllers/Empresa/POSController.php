<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class POSController extends Controller
{
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $products = Product::with('images')
            ->where('empresa_id', $empresaId)
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        $productsData = $products->map(function ($p) {

            $img = optional($p->images->first())->path;

            return [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => (float) $p->price,
                'img'   => $img
                    ? asset('storage/'.$img)
                    : asset('images/no-image.png'),
            ];
        });

        return view('empresa.pos.index', compact('productsData'));
    }

    public function store(Request $request)
    {
        try {

            $empresaId = Auth::user()->empresa_id;
            $userId    = Auth::id();

            $items = $request->items ?? [];

            if (empty($items)) {
                return response()->json([
                    'ok' => false,
                    'error' => 'No hay items'
                ]);
            }

            DB::beginTransaction();

            // ================= CABECERA =================
            $ventaId = DB::table('ventas')->insertGetId([
                'empresa_id' => $empresaId,
                'user_id'    => $userId,

                // ESTRUCTURA REAL
                'subtotal'   => $request->total_sin_iva ?? 0,
                'iva'        => $request->total_iva ?? 0,
                'total'      => $request->total_con_iva ?? 0,

                'cliente_condicion' => 'consumidor_final',
                'descuento'  => 0,
                'metodo_pago'=> $request->metodo_pago ?? 'efectivo',
                'monto_pagado'=> $request->monto_pagado ?? 0,
                'vuelto'     => $request->vuelto ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ================= ITEMS =================
            foreach ($items as $item) {

                DB::table('venta_items')->insert([
                    'venta_id'       => $ventaId,
                    'product_id'     => $item['product_id'] ?? null,
                    'cantidad'       => $item['cantidad'] ?? 1,

                    // ESTRUCTURA REAL
                    'precio_unitario'=> $item['precio'] ?? 0,
                    'subtotal'       => $item['total'] ?? 0,

                    'created_at'     => now(),
                    'updated_at'     => now(),
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
                'error' => $e->getMessage(),
                'line'  => $e->getLine()
            ]);
        }
    }
}
