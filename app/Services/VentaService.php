<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Product;
use App\Models\Client;
use App\Models\KardexMovimiento;
use App\Models\ClientLedger;
use App\Models\ProductVariant;

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

    public function registrarVenta($user, array $items, $clienteId = null, $tipoVentaCliente = 'contado', $tipoComprobante = 'ticket', $hacerRemito = false, $itemsEntregados = null, $metodoPago = 'efectivo', $montoEntrega = null, $pagosDiferenciados = [], $finanza_cuenta_id = null): Venta
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

        return DB::transaction(function () use ($user, $items, $clienteId, $tipoVentaCliente, $tipoComprobante, $hacerRemito, $itemsEntregados, $metodoPago, $montoEntrega, $pagosDiferenciados) {

            // Re-obtener empresa con bloqueo para asegurar número correlativo único
            $empresaActual = \App\Models\Empresa::where('id', $user->empresa_id)->lockForUpdate()->first();

            $totalSinIva = 0;
            $totalIva    = 0;
            $totalConIva = 0;


            /*
            |--------------------------------------------------------------------------
            | CREAR CABECERA DE VENTA
            |--------------------------------------------------------------------------
            */

            $numeroComprobante = $this->generarNumeroComprobante($empresaActual, $tipoComprobante);
            $cae = null;
            $caeVencimiento = null;
            $qrData = null;
            $afipError = null;

            // FACTURACIÓN ELECTRÓNICA AFIP (AUTO-DETECCIÓN)
            // Si la empresa tiene CUIT configurado, AFIP ACTIVO y el comprobante es genérico, lo convertimos a fiscal.
            if ($empresaActual->arca_cuit && $empresaActual->arca_activo && in_array($tipoComprobante, ['ticket', 'factura', 'F', 'B', 'C', 'X', 'NC', 'nota_credito'])) {
                
                // Mapeo automático de Ticket -> Factura Fiscal según condición IVA
                if ($tipoComprobante === 'ticket' || $tipoComprobante === 'X' || $tipoComprobante === 'factura') {
                    if ($empresaActual->condicion_iva === 'Monotributista') {
                        $tipoComprobante = 'C';
                    } else {
                        // Si es Responsable Inscripto, determinamos A o B según el cliente
                        $cliente = $clienteId ? \App\Models\Client::find($clienteId) : null;
                        if ($cliente && $cliente->document_type === 'CUIT' && $cliente->iva_condition === 'Responsable Inscripto') {
                            $tipoComprobante = 'A';
                        } else {
                            $tipoComprobante = 'B';
                        }
                    }
                }

                try {
                    $totales = $this->calcularTotalesItems($items, $empresaActual);
                    $ventaFicticia = (object) [
                        'tipo_comprobante' => $tipoComprobante,
                        'total_con_iva' => $totales['total_con_iva'],
                        'total_sin_iva' => $totales['total_sin_iva'],
                        'total_iva' => $totales['total_iva'],
                        'cliente' => $clienteId ? \App\Models\Client::find($clienteId) : null
                    ];

                    $resAfip = app(\App\Services\AfipService::class)->solicitarCAE($empresaActual, $ventaFicticia);

                    if ($resAfip['success']) {
                        $cae = $resAfip['cae'];
                        $caeVencimiento = date('Y-m-d', strtotime($resAfip['cae_vencimiento']));
                        $numeroComprobante = $resAfip['numero_comprobante'];
                        $qrData = $resAfip['qr_data'] ?? null;
                    } else {
                         // TRABAR LA VENTA SI NO HAY CAE LEGAL
                        throw new \Exception("ERROR AFIP: " . $resAfip['error']);
                    }
                } catch (\Exception $e) {
                    throw new \Exception("BLOQUEO FISCAL: " . $e->getMessage());
                }
            }

            $venta = Venta::create([
                'empresa_id'         => $empresaActual->id,
                'user_id'            => $user->id,
                'client_id'          => $clienteId,
                'tipo_comprobante'   => $tipoComprobante,
                'numero_comprobante' => $numeroComprobante,
                'cae'                => $cae,
                'cae_vencimiento'    => $caeVencimiento,
                'qr_data'            => $qrData,
                'afip_error'         => $afipError,
                'metodo_pago'        => $metodoPago, 
                'total_sin_iva'      => 0,
                'total_iva'          => 0,
                'total_con_iva'      => 0,
            ]);

            // Incrementar el próximo número en la empresa solo si no usamos AFIP 
            // (AFIP ya nos da el número correlativo oficial)
            if (!$cae) {
                $empresaActual->proximo_numero_factura++;
                $empresaActual->save();
            }


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

                $productId = $item['id'];
                $variantId = $item['variant_id'] ?? null;
                $cantidad  = (float) $item['quantity'];

                $product = Product::where('id', $productId)
                    ->where('empresa_id', $empresaActual->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $variant = $variantId ? ProductVariant::find($variantId) : null;


                if ($cantidad <= 0) {
                    throw new \Exception("Cantidad inválida en venta");
                }


                /*
                |--------------------------------------------------------------------------
                | PRECIO FINAL (YA INCLUYE IVA)
                |--------------------------------------------------------------------------
                */

                $precioFinalUnitario = (float) ($item['price'] ?? ($variant ? ($variant->price ?: $product->price) : $product->price));
                $totalItemConIva     = $precioFinalUnitario * $cantidad;


                /*
                |--------------------------------------------------------------------------
                | DESGLOSE IVA (ESTÁNDAR AFIP)
                |--------------------------------------------------------------------------
                */

                // Calculamos hacia atrás desde el total real que paga el cliente
                $subtotalItemSinIva = round($totalItemConIva / 1.21, 2);
                $ivaItem            = round($totalItemConIva - $subtotalItemSinIva, 2);
                $precioSinIvaUnitario = round($subtotalItemSinIva / $cantidad, 4);


                /*
                |--------------------------------------------------------------------------
                | REGISTRAR ITEM DE VENTA
                |--------------------------------------------------------------------------
                */

                VentaItem::create([
                    'venta_id'                => $venta->id,
                    'product_id'              => $product->id,
                    'variant_id'              => $variantId,
                    'cantidad'                => $cantidad,
                    'precio_unitario_sin_iva' => $precioSinIvaUnitario,
                    'subtotal_item_sin_iva'   => $subtotalItemSinIva,
                    'iva_item'                => $ivaItem,
                    'total_item_con_iva'      => $totalItemConIva,
                ]);


                /*
                |--------------------------------------------------------------------------
                | MOVIMIENTO DE STOCK (Invertido si es NC)
                |--------------------------------------------------------------------------
                */

                $esNC = ($tipoComprobante === 'NC' || $tipoComprobante === 'nota_credito');

                if ($variant) {
                    if ($esNC) {
                        $variant->aumentarStock($cantidad, 'Nota de Crédito #' . $venta->id);
                    } else {
                        $variant->descontarStock($cantidad, 'Venta #' . $venta->id);
                    }
                } else {
                    if ($esNC) {
                        $product->aumentarStock($cantidad, 'Nota de Crédito #' . $venta->id);
                    } else {
                        $product->descontarStock($cantidad, 'Venta #' . $venta->id);
                    }
                }


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
            | NC -> Saldo a favor del cliente (Credit)
            | Venta -> Deuda del cliente (Debit)
            */

            if ($clienteId && $tipoVentaCliente === 'cuenta_corriente') {

                $ledgerDeuda = ClientLedger::create([
                    'empresa_id'     => $empresaActual->id,
                    'client_id'      => $clienteId,
                    'reference_type' => Venta::class,
                    'reference_id'   => $venta->id,
                    'type'           => $esNC ? 'credit' : 'debit',
                    'amount'         => $totalConIva,
                    'pending_amount' => $esNC ? 0 : $totalConIva,
                    'paid'           => $esNC ? 1 : 0, // Las NC se consideran "cerradas"
                    'description'    => ($esNC ? 'Nota de Crédito #' : 'Venta #') . $venta->numero_comprobante,
                ]);

                // Si el cliente hizo un pago parcial INICIAL (Ej: Deuda de 98k, pagó 20k, 78k van a saldo)
                if (!$esNC && isset($montoEntrega) && $montoEntrega > 0) {
                    app(\App\Services\ClientAccountService::class)->registrarCobro(
                        $clienteId,
                        $montoEntrega,
                        $metodoPago,
                        null,                           // referencia
                        now()->format('Y-m-d'),         // fecha
                        [$ledgerDeuda->id],             // facturasEspecificas (solo PAGA a esta misma factura recién nacida)
                        $pagosDiferenciados,            // array de caja/bancos
                        true                            // autoImputar (aplicará los fondos al ID específico enviado)
                    );
                }
            }



            /*
            |--------------------------------------------------------------------------
            | IMPACTO FINANCIERO (CAJA/BANCO)
            |--------------------------------------------------------------------------
            | Si es venta al contado y tenemos una cuenta de finanzas, impactamos.
            | Si es cuenta corriente, el cobro se maneja por separado (o vía registrarCobro).
            */

            if ($tipoVentaCliente === 'contado' && $finanza_cuenta_id) {
                
                $cuenta = \App\Models\FinanzaCuenta::where('empresa_id', $empresaActual->id)
                    ->where('id', $finanza_cuenta_id)
                    ->first();

                if ($cuenta) {
                    \App\Models\FinanzaMovimiento::create([
                        'empresa_id'   => $empresaActual->id,
                        'cuenta_id'    => $cuenta->id,
                        'user_id'      => $user->id,
                        'tipo'         => 'ingreso',
                        'monto'        => $totalConIva,
                        'fecha'        => now(),
                        'concepto'     => "Venta #{$venta->numero_comprobante}",
                        'categoria'    => 'Ventas',
                        'reference_type' => Venta::class,
                        'reference_id'   => $venta->id,
                    ]);

                    // Actualizar saldo de la cuenta
                    $cuenta->increment('saldo_actual', $totalConIva);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | GESTIÓN LOGÍSTICA (REMITOS)
            |--------------------------------------------------------------------------
            | Solo generamos remito si el usuario lo marcó explícitamente.
            | Esto evita la queja del usuario sobre remitos automáticos no deseados.
            |--------------------------------------------------------------------------
            */

            // SI SE MARCO EXPLÍCITAMENTE HACER REMITO (Entrega Parcial / En Guarda)
            if ($hacerRemito && !$esNC) {
                if (!empty($itemsEntregados)) {
                    $remito = $this->generarRemitoParcialInicial($venta, $user, $empresaActual, $itemsEntregados);
                } else {
                    // Si marcó remito pero no especificó cantidades, entregamos 100% por defecto pero con documento
                    $remito = $this->generarRemitoAutomatico($venta, $user, $empresaActual);
                }
                $venta->setRelation('remito_principal', $remito);
            }

            // REGISTRAR ACTIVIDAD
            \App\Models\ActivityLog::log(
                "Registró venta #{$venta->numero_comprobante} por $" . number_format($venta->total_con_iva, 2, ',', '.'),
                $venta
            );

            return $venta;

        });
    }

    /**
     * Crea un Remito por el 100% de los items de la venta.
     */
    protected function generarRemitoAutomatico($venta, $user, $empresa)
    {
        $numeroRemito = $this->generarNumeroRemito($empresa);

        $remito = \App\Models\Remito::create([
            'empresa_id'     => $empresa->id,
            'venta_id'       => $venta->id,
            'user_id'        => $user->id,
            'client_id'      => $venta->client_id,
            'numero_remito'  => $numeroRemito,
            'fecha_entrega'  => now(),
            'observaciones'  => 'Entrega total automática al registrar venta.',
        ]);

        // Incrementar contador remitos
        $empresa->proximo_numero_remito++;
        $empresa->save();

        foreach ($venta->items as $item) {
            \App\Models\RemitoItem::create([
                'remito_id'     => $remito->id,
                'venta_item_id' => $item->id,
                'product_id'    => $item->product_id,
                'variant_id'    => $item->variant_id,
                'cantidad'      => $item->cantidad,
            ]);

            // Marcar como entregado al 100%
            $item->cantidad_entregada = $item->cantidad;
            $item->save();
        }

        return $remito;
    }

    /**
     * Crea un Remito parcial basado en lo que el usuario informó en el POS.
     */
    protected function generarRemitoParcialInicial($venta, $user, $empresa, $itemsEntregados)
    {
        $numeroRemito = $this->generarNumeroRemito($empresa);

        $remito = \App\Models\Remito::create([
            'empresa_id'     => $empresa->id,
            'venta_id'       => $venta->id,
            'user_id'        => $user->id,
            'client_id'      => $venta->client_id,
            'numero_remito'  => $numeroRemito,
            'fecha_entrega'  => now(),
            'observaciones'  => 'Entrega parcial inmediata (Retira en el momento).',
        ]);

        // Incrementar contador remitos
        $empresa->proximo_numero_remito++;
        $empresa->save();

        foreach ($itemsEntregados as $e) {
            $qtyEntrega = (float) $e['quantity_delivery'];
            if ($qtyEntrega <= 0) continue;

            // Buscar la línea de venta correspondiente
            $itemVenta = $venta->items()
                ->where('product_id', $e['id'])
                ->where('variant_id', $e['variant_id'] ?: null)
                ->first();

            if ($itemVenta) {
                // No entregar más de lo vendido por seguridad
                if ($qtyEntrega > $itemVenta->cantidad) $qtyEntrega = $itemVenta->cantidad;

                \App\Models\RemitoItem::create([
                    'remito_id'     => $remito->id,
                    'venta_item_id' => $itemVenta->id,
                    'product_id'    => $itemVenta->product_id,
                    'variant_id'    => $itemVenta->variant_id,
                    'cantidad'      => $qtyEntrega,
                ]);

                $itemVenta->cantidad_entregada = $qtyEntrega;
                $itemVenta->save();
            }
        }

        return $remito;
    }

    public function calcularTotalesItems($items, $empresa)
    {
        $totalSinIva = 0;
        $totalIva    = 0;
        $totalConIva = 0;

        foreach ($items as $item) {
            $product = \App\Models\Product::find($item['id']);
            $variant = isset($item['variant_id']) ? \App\Models\ProductVariant::find($item['variant_id']) : null;
            
            $precioFinalUnitario = (float) ($item['price'] ?? ($variant ? ($variant->price ?: $product->price) : $product->price));
            $cantidad  = (float) $item['quantity'];

            $totalItemConIva     = $precioFinalUnitario * $cantidad;
            $precioSinIvaUnitario = round($precioFinalUnitario / 1.21, 2);
            $subtotalItemSinIva   = round($precioSinIvaUnitario * $cantidad, 2);
            $ivaItem              = round($totalItemConIva - $subtotalItemSinIva, 2);

            $totalSinIva += $subtotalItemSinIva;
            $totalIva    += $ivaItem;
            $totalConIva += $totalItemConIva;
        }

        return [
            'total_sin_iva' => $totalSinIva,
            'total_iva'     => $totalIva,
            'total_con_iva' => $totalConIva
        ];
    }

    protected function generarNumeroRemito($empresa)
    {
        $pv = str_pad($empresa->punto_venta ?? 1, 4, '0', STR_PAD_LEFT);
        $next = $empresa->proximo_numero_remito ?? 1;
        return $pv . '-' . str_pad($next, 8, '0', STR_PAD_LEFT);
    }
    protected function generarNumeroComprobante($empresa, $tipo)
    {
        $pv = str_pad($empresa->arca_punto_venta ?? ($empresa->punto_venta ?? 1), 5, '0', STR_PAD_LEFT);
        $next = $empresa->proximo_numero_factura ?? 1;
        
        return $pv . '-' . str_pad($next, 8, '0', STR_PAD_LEFT);
    }
}
