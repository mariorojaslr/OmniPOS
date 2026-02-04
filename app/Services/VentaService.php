<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class VentaService
{
    public function registrarVenta($user, array $items): Venta
    {
        $empresa = $user->empresa;

        return DB::transaction(function () use ($user, $empresa, $items) {

            $totalSinIva = 0;
            $totalIva    = 0;
            $totalConIva = 0;

            $venta = Venta::create([
                'empresa_id'    => $empresa->id,
                'user_id'       => $user->id,
                'total_sin_iva' => 0,
                'total_iva'     => 0,
                'total_con_iva' => 0,
            ]);

            foreach ($items as $item) {

                $product = Product::where('id', $item['id'])
                    ->where('empresa_id', $empresa->id)
                    ->firstOrFail();

                $cantidad = $item['quantity'];

                $precioUnitarioSinIva = $product->price;
                $subtotalItemSinIva   = $precioUnitarioSinIva * $cantidad;
                $ivaItem              = round($subtotalItemSinIva * 0.21, 2);
                $totalItemConIva      = $subtotalItemSinIva + $ivaItem;

                VentaItem::create([
                    'venta_id'                => $venta->id,
                    'product_id'              => $product->id,
                    'cantidad'                => $cantidad,
                    'precio_unitario_sin_iva' => $precioUnitarioSinIva,
                    'subtotal_item_sin_iva'   => $subtotalItemSinIva,
                    'iva_item'                => $ivaItem,
                    'total_item_con_iva'      => $totalItemConIva,
                ]);

                $totalSinIva += $subtotalItemSinIva;
                $totalIva    += $ivaItem;
                $totalConIva += $totalItemConIva;
            }

            $venta->update([
                'total_sin_iva' => $totalSinIva,
                'total_iva'     => $totalIva,
                'total_con_iva' => $totalConIva,
            ]);

            return $venta;
        });
    }
}
