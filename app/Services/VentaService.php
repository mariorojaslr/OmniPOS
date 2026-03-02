<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Product;
use App\Models\KardexMovimiento;
use App\Models\ClientLedger;
use Illuminate\Support\Facades\DB;

class VentaService
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRAR VENTA COMPLETA
    |--------------------------------------------------------------------------
    | • Guarda venta
    | • Guarda items
    | • Descuenta stock automático
    | • Genera Kardex automático
    | • Soporta cliente opcional
    | • Soporta cuenta corriente
    | • PRECIOS CON IVA INCLUIDO (CORREGIDO)
    |--------------------------------------------------------------------------
    */
    public function registrarVenta($user, array $items, $clienteId = null, $tipoVentaCliente = 'contado'): Venta
    {
        $empresa = $user->empresa;

        return DB::transaction(function () use ($user, $empresa, $items, $clienteId, $tipoVentaCliente) {

            $totalSinIva = 0;
            $totalIva    = 0;
            $totalConIva = 0;

            /*
            |--------------------------------------------------------------------------
            | CREAR VENTA
            |--------------------------------------------------------------------------
            */
            $venta = Venta::create([
                'empresa_id'    => $empresa->id,
                'user_id'       => $user->id,
                'client_id'     => $clienteId,
                'total_sin_iva' => 0,
                'total_iva'     => 0,
                'total_con_iva' => 0,
            ]);

            /*
            |--------------------------------------------------------------------------
            | PROCESAR ITEMS (PRECIO YA INCLUYE IVA)
            |--------------------------------------------------------------------------
            */
            foreach ($items as $item) {

                $product = Product::where('id', $item['id'])
                    ->where('empresa_id', $empresa->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $cantidad = (float) $item['quantity'];

                /*
                |--------------------------------------------------------------------------
                | PRECIO FINAL (YA INCLUYE IVA)
                |--------------------------------------------------------------------------
                */
                $precioFinalUnitario = (float) $product->price;
                $totalItemConIva     = $precioFinalUnitario * $cantidad;

                /*
                |--------------------------------------------------------------------------
                | DESGLOSAR IVA HACIA ABAJO
                |--------------------------------------------------------------------------
                */
                $precioSinIvaUnitario = round($precioFinalUnitario / 1.21, 2);
                $subtotalItemSinIva   = round($precioSinIvaUnitario * $cantidad, 2);
                $ivaItem              = round($totalItemConIva - $subtotalItemSinIva, 2);

                /*
                |--------------------------------------------------------------------------
                | GUARDAR ITEM
                |--------------------------------------------------------------------------
                */
                VentaItem::create([
                    'venta_id'                => $venta->id,
                    'product_id'              => $product->id,
                    'cantidad'                => $cantidad,
                    'precio_unitario_sin_iva' => $precioSinIvaUnitario,
                    'subtotal_item_sin_iva'   => $subtotalItemSinIva,
                    'iva_item'                => $ivaItem,
                    'total_item_con_iva'      => $totalItemConIva,
                ]);

                /*
                |--------------------------------------------------------------------------
                | DESCONTAR STOCK
                |--------------------------------------------------------------------------
                */
                $stockAnterior = (float) $product->stock;
                $stockNuevo    = $stockAnterior - $cantidad;

                $product->stock = $stockNuevo;
                $product->save();

                /*
                |--------------------------------------------------------------------------
                | KARDEX
                |--------------------------------------------------------------------------
                */
                KardexMovimiento::create([
                    'empresa_id'       => $empresa->id,
                    'product_id'       => $product->id,
                    'user_id'          => $user->id,
                    'tipo'             => 'salida',
                    'cantidad'         => $cantidad,
                    'stock_resultante' => $stockNuevo,
                    'origen'           => 'Venta #' . $venta->id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | ACUMULAR TOTALES
                |--------------------------------------------------------------------------
                */
                $totalSinIva += $subtotalItemSinIva;
                $totalIva    += $ivaItem;
                $totalConIva += $totalItemConIva;
            }

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR TOTALES
            |--------------------------------------------------------------------------
            */
            $venta->update([
                'total_sin_iva' => $totalSinIva,
                'total_iva'     => $totalIva,
                'total_con_iva' => $totalConIva,
            ]);

            /*
            |--------------------------------------------------------------------------
            | CUENTA CORRIENTE
            |--------------------------------------------------------------------------
            */
            if ($clienteId && $tipoVentaCliente === 'cuenta_corriente') {

                ClientLedger::create([
                    'empresa_id'  => $empresa->id,
                    'client_id'   => $clienteId,
                    'user_id'     => $user->id,
                    'type'        => 'debit',
                    'amount'      => $totalConIva,
                    'paid'        => 0,
                    'description' => 'Venta POS #' . $venta->id,
                ]);
            }

            return $venta;
        });
    }
}
