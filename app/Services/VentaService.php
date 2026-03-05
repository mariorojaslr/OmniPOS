<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Product;
use App\Models\Client;
use App\Models\KardexMovimiento;
use App\Models\ClientLedger;

use Illuminate\Support\Facades\DB;

class VentaService
{

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR VENTA COMPLETA
    |--------------------------------------------------------------------------
    | RESPONSABILIDADES:
    |
    | • Crear registro de venta
    | • Registrar items vendidos
    | • Descontar stock del producto
    | • Registrar movimiento en Kardex
    | • Calcular totales con IVA incluido
    | • Registrar deuda en cuenta corriente si aplica
    |
    | IMPORTANTE
    | Los precios de productos ya incluyen IVA.
    | El sistema calcula el desglose hacia abajo.
    |--------------------------------------------------------------------------
    */

    public function registrarVenta($user, array $items, $clienteId = null, $tipoVentaCliente = 'contado'): Venta
    {
        $empresa = $user->empresa;

        /*
        |--------------------------------------------------------------------------
        | CLIENTE CONSUMIDOR FINAL AUTOMÁTICO
        |--------------------------------------------------------------------------
        | Si el POS no envía cliente se asigna automáticamente
        | el cliente "Consumidor Final" de la empresa.
        */

        if (!$clienteId) {

            $cliente = Client::where('empresa_id', $empresa->id)
                ->where('name', 'Consumidor Final')
                ->first();

            if ($cliente) {
                $clienteId = $cliente->id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACCIÓN COMPLETA
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use ($user, $empresa, $items, $clienteId, $tipoVentaCliente) {

            $totalSinIva = 0;
            $totalIva    = 0;
            $totalConIva = 0;


            /*
            |--------------------------------------------------------------------------
            | CREAR CABECERA DE VENTA
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
            | PROCESAR ITEMS DE LA VENTA
            |--------------------------------------------------------------------------
            */

            foreach ($items as $item) {

                /*
                |--------------------------------------------------------------------------
                | OBTENER PRODUCTO CON BLOQUEO
                |--------------------------------------------------------------------------
                */

                $product = Product::where('id', $item['id'])
                    ->where('empresa_id', $empresa->id)
                    ->lockForUpdate()
                    ->firstOrFail();


                $cantidad = (float) $item['quantity'];

                if ($cantidad <= 0) {
                    throw new \Exception("Cantidad inválida en venta");
                }


                /*
                |--------------------------------------------------------------------------
                | PRECIO FINAL (YA INCLUYE IVA)
                |--------------------------------------------------------------------------
                */

                $precioFinalUnitario = (float) $product->price;
                $totalItemConIva     = $precioFinalUnitario * $cantidad;


                /*
                |--------------------------------------------------------------------------
                | DESGLOSE IVA
                |--------------------------------------------------------------------------
                */

                $precioSinIvaUnitario = round($precioFinalUnitario / 1.21, 2);
                $subtotalItemSinIva   = round($precioSinIvaUnitario * $cantidad, 2);
                $ivaItem              = round($totalItemConIva - $subtotalItemSinIva, 2);


                /*
                |--------------------------------------------------------------------------
                | REGISTRAR ITEM DE VENTA
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

                /*
                |--------------------------------------------------------------------------
                | PROTECCIÓN INVENTARIO
                |--------------------------------------------------------------------------
                */

                if ($stockNuevo < 0) {

                    throw new \Exception(
                        "Stock insuficiente para el producto: {$product->name}"
                    );
                }

                $product->stock = $stockNuevo;
                $product->save();


                /*
                |--------------------------------------------------------------------------
                | REGISTRAR MOVIMIENTO EN KARDEX
                |--------------------------------------------------------------------------
                */

                KardexMovimiento::create([
                    'empresa_id'       => $empresa->id,
                    'product_id'       => $product->id,
                    'user_id'          => $user->id,
                    'tipo'             => 'salida',
                    'cantidad'         => -$cantidad,
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
            | ACTUALIZAR TOTALES DE LA VENTA
            |--------------------------------------------------------------------------
            */

            $venta->update([
                'total_sin_iva' => $totalSinIva,
                'total_iva'     => $totalIva,
                'total_con_iva' => $totalConIva,
            ]);


            /*
            |--------------------------------------------------------------------------
            | REGISTRAR CUENTA CORRIENTE
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


            /*
            |--------------------------------------------------------------------------
            | RETORNAR VENTA
            |--------------------------------------------------------------------------
            */

            return $venta;

        });
    }
}
