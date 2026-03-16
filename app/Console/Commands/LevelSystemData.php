<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class LevelSystemData extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'db:level-data {--empresa= : ID de la empresa a omitir (opcional)} {--debug : Muestra conteos de tablas}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Nivela precios, IVA y stock de todos los registros del sistema para la nueva arquitectura';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $omitEmpresaId = $this->option('empresa');
        $debug = $this->option('debug');
        
        $this->info("------------------------------------------------------------");
        $this->info("🚀 INICIANDO NIVELACIÓN DE DATOS - MULTIPOS");
        $this->info("------------------------------------------------------------");

        if ($debug) {
            $this->warn("DEBUG: Conteo total de registros en la DB:");
            $this->line("Ventas: " . Venta::count());
            $this->line("Ventas (Items): " . VentaItem::count());
            $this->line("Productos: " . Product::count());
            $this->line("Items Compra: " . PurchaseItem::count());
            $this->info("------------------------------------------------------------");
        }
        
        if ($omitEmpresaId) {
            $this->warn("⚠️  Omitiendo Empresa ID: $omitEmpresaId (Pruebas/Demo)");
        }

        try {
            DB::transaction(function () use ($omitEmpresaId, $debug) {
                
                // 1. NIVELAR VENTAS (Cabecera)
                $this->comment("1/4 Procesando Cabeceras de Ventas...");
                $ventasQuery = Venta::query();
                if ($omitEmpresaId) $ventasQuery->where('empresa_id', '!=', $omitEmpresaId);
                
                $countVentas = 0;
                $ventasQuery->each(function ($venta) use (&$countVentas, $debug) {
                    // Valor base: intentamos sacar el total de donde sea
                    $total_con_iva = (float)$venta->total_con_iva;
                    $total_normal  = (float)$venta->total;
                    $ivaValue      = (float)$venta->iva;

                    $totalFinal = $total_con_iva > 0 ? $total_con_iva : ($total_normal > 0 ? $total_normal : 0);

                    // CASO EMERGENCIA: Si no hay total pero hay IVA (pasa en registros corruptos de Hostinger)
                    // Reconstruimos: Total = IVA / 0.1735... (o mas simple, Subtotal = IVA / 0.21)
                    if ($totalFinal <= 0 && $ivaValue > 0) {
                        $sub = $ivaValue / 0.21;
                        $totalFinal = round($sub + $ivaValue, 2);
                    }
                    
                    if ($debug && $countVentas < 10) {
                        $this->line("DEBUG: Venta #{$venta->id} -> Total Detectado: $totalFinal | IVA Original: $ivaValue");
                    }

                    if ($totalFinal > 0) {
                        $subtotal = $totalFinal / 1.21;
                        $venta->subtotal = round($subtotal, 2);
                        $venta->iva      = round($totalFinal - $subtotal, 2);
                        $venta->total    = $totalFinal; 
                        $venta->save();
                        $countVentas++;
                    }
                });
                $this->info("✔ $countVentas ventas niveladas.");

                // 2. NIVELAR ITEMS DE VENTA (Detalle)
                $this->comment("2/4 Procesando Detalle de Ventas (Items)...");
                $countItemsVenta = 0;
                
                $itemsQuery = VentaItem::whereHas('venta', function($q) use ($omitEmpresaId){
                    if($omitEmpresaId) $q->where('empresa_id', '!=', $omitEmpresaId);
                });

                $itemsQuery->each(function ($item) use (&$countItemsVenta, $debug) {
                    // Detectar precio/total de cualquier lado
                    // Hostinger puede tener nombres antiguos como 'precio_unitario' o 'subtotal'
                    if ($debug && $countItemsVenta < 3) {
                        $this->line("DEBUG: Item #{$item->id} full data: " . json_encode($item->getAttributes()));
                    }

                    $totalItem = (float)($item->total_item_con_iva ?: $item->total ?: $item->subtotal ?: $item->precio ?: $item->precio_unitario ?: 0);
                    $cantidad  = (float)$item->cantidad;

                    // Si el totalItem sigue siendo 0 pero la cantidad > 0, 
                    // intentamos reconstruir desde el precio_unitario * cantidad si existiera
                    if ($totalItem <= 0 && (float)$item->precio_unitario > 0) {
                        $totalItem = (float)$item->precio_unitario * $cantidad;
                    }

                    if ($totalItem > 0 && $cantidad > 0) {
                        $baseTotal = $totalItem / 1.21;
                        $item->precio_unitario_sin_iva = round($baseTotal / $cantidad, 2);
                        $item->subtotal_item_sin_iva   = round($baseTotal, 2);
                        $item->iva_item                = round($totalItem - $baseTotal, 2);
                        $item->total_item_con_iva      = $totalItem; // Estandarizamos
                        $item->save();
                        $countItemsVenta++;
                    }
                });
                $this->info("✔ $countItemsVenta items de venta nivelados.");

                // 3. NIVELAR PRODUCTOS (Precios y Stocks)
                $this->comment("3/4 Procesando Catálogo de Productos...");
                $productsQuery = Product::query();
                if ($omitEmpresaId) $productsQuery->where('empresa_id', '!=', $omitEmpresaId);
                
                $countProdPrecios = 0;
                $countProdStock = 0;

                $productsQuery->with('variants')->each(function ($product) use (&$countProdPrecios, &$countProdStock) {
                    // Nivelar Precio si tiene cargado el price (final)
                    $precioFinal = (float)($product->price ?? $product->precio ?? 0);
                    if ($precioFinal > 0) {
                        $product->precio_sin_iva = round($precioFinal / 1.21, 2);
                        $product->save();
                        $countProdPrecios++;
                    }

                    // Nivelar Stock si tiene variantes
                    if ($product->variants->count() > 0) {
                        $oldStock = (float)$product->stock;
                        $newStock = (float)$product->variants->sum('stock');
                        
                        if ($oldStock != $newStock) {
                            $product->stock = $newStock;
                            $product->save();
                            
                            \App\Models\KardexMovimiento::create([
                                'empresa_id'       => $product->empresa_id,
                                'product_id'       => $product->id,
                                'user_id'          => 1, 
                                'tipo'             => 'entrada',
                                'cantidad'         => abs($newStock - $oldStock),
                                'stock_resultante' => $newStock,
                                'origen'           => "AJUSTE DE NIVELACIÓN",
                            ]);
                            $countProdStock++;
                        }
                    }
                });
                $this->info("✔ $countProdPrecios precios de productos nivelados.");
                $this->info("✔ $countProdStock stocks sincronizados con variantes.");

            });

            $this->info("------------------------------------------------------------");
            $this->info("✅ PROCESO COMPLETADO EXITOSAMENTE");
            $this->info("------------------------------------------------------------");

        } catch (\Exception $e) {
            $this->error("❌ ERROR DURANTE LA NIVELACIÓN: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
