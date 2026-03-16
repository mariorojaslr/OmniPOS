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
                    // Intentamos obtener el total de cualquier columna
                    $totalValue = (float)($venta->total_con_iva ?: $venta->total ?: $venta->total_venta ?: 0);
                    $ivaValue   = (float)$venta->iva;

                    // CASO ESPECIAL HOSTINGER: Si total es 0 pero hay IVA, reconstruimos el total
                    if ($totalValue <= 0 && $ivaValue > 0) {
                        $totalValue = round($ivaValue / (0.21 / 1.21), 2);
                    }
                    
                    if ($debug && $countVentas < 5) {
                        $this->line("DEBUG: Venta ID {$venta->id} - Total Detectado: $totalValue - IVA: $ivaValue");
                    }

                    if ($totalValue > 0) {
                        $subtotal = $totalValue / 1.21;
                        $venta->subtotal = round($subtotal, 2);
                        $venta->iva      = round($totalValue - $subtotal, 2);
                        $venta->total    = $totalValue; 
                        $venta->save();
                        $countVentas++;
                    }
                });
                $this->info("✔ $countVentas ventas niveladas.");

                // 2. NIVELAR ITEMS DE VENTA (Detalle)
                $this->comment("2/4 Procesando Detalle de Ventas (Items)...");
                $countItemsVenta = 0;
                VentaItem::whereHas('venta', function($q) use ($omitEmpresaId){
                    if($omitEmpresaId) $q->where('empresa_id', '!=', $omitEmpresaId);
                })->each(function ($item) use (&$countItemsVenta) {
                    $total_item = (float)($item->total_item_con_iva ?? $item->total ?? $item->subtotal ?? 0);
                    if ($total_item > 0 && (float)$item->cantidad > 0) {
                        $base_total = $total_item / 1.21;
                        $item->precio_unitario_sin_iva = round($base_total / $item->cantidad, 2);
                        $item->subtotal_item_sin_iva   = round($base_total, 2);
                        $item->iva_item                = round($total_item - $base_total, 2);
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
