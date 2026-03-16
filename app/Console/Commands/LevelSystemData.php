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
            DB::transaction(function () use ($omitEmpresaId) {
                
                // 1. NIVELAR VENTAS (Cabecera)
                $this->comment("1/4 Procesando Cabeceras de Ventas...");
                $ventasQuery = Venta::query();
                if ($omitEmpresaId) $ventasQuery->where('empresa_id', '!=', $omitEmpresaId);
                
                $countVentas = 0;
                $ventasQuery->each(function ($venta) use (&$countVentas) {
                    // En producción el total real está en total_con_iva o total
                    $total = (float)($venta->total_con_iva ?? $venta->total ?? 0);
                    
                    if ($total > 0) {
                        $subtotal = $total / 1.21;
                        $venta->subtotal = round($subtotal, 2);
                        $venta->iva      = round($total - $subtotal, 2);
                        // Aseguramos que 'total' también tenga el valor
                        $venta->total    = $total; 
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
                    $total_item = (float)$item->total_item_con_iva;
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

                // 3. NIVELAR COMPRAS (Costos)
                $this->comment("3/4 Procesando Items de Compra...");
                $countItemsCompra = 0;
                PurchaseItem::whereHas('purchase', function($q) use ($omitEmpresaId){
                    if($omitEmpresaId) $q->where('empresa_id', '!=', $omitEmpresaId);
                })->each(function ($item) use (&$countItemsCompra) {
                    // En producción el 'cost' guardado era el final. 
                    // Lo movemos a base + iva.
                    $costFinal = (float)$item->cost;
                    if ($costFinal > 0) {
                        $costBase = $costFinal / 1.21;
                        $item->cost = round($costBase, 2);
                        $item->iva  = round($costFinal - $costBase, 2);
                        $item->save();
                        $countItemsCompra++;
                    }
                });
                $this->info("✔ $countItemsCompra items de compra nivelados.");

                // 4. SINCRONIZAR STOCK (Variantes -> Producto)
                $this->comment("4/4 Sincronizando Stock con Variantes y Kardex...");
                $productsQuery = Product::query();
                if ($omitEmpresaId) $productsQuery->where('empresa_id', '!=', $omitEmpresaId);

                $countProducts = 0;
                $productsQuery->with('variants')->each(function ($product) use (&$countProducts) {
                    if ($product->variants->count() > 0) {
                        $oldStock = (float)$product->stock;
                        $newStock = (float)$product->variants->sum('stock');
                        
                        if ($oldStock != $newStock) {
                            $diff = $newStock - $oldStock;
                            
                            // Actualizar producto
                            $product->stock = $newStock;
                            $product->save();

                            // Crear movimiento de Kardex explicativo
                            \App\Models\KardexMovimiento::create([
                                'empresa_id'       => $product->empresa_id,
                                'product_id'       => $product->id,
                                'user_id'          => auth()->id() ?? 1, // Default to 1 if console
                                'tipo'             => $diff > 0 ? 'entrada' : 'salida',
                                'cantidad'         => $diff,
                                'stock_resultante' => $newStock,
                                'origen'           => "AJUSTE DE NIVELACIÓN (Sincronización con Variantes)",
                            ]);
                        }
                        $countProducts++;
                    }
                });
                $this->info("✔ $countProducts productos sincronizados.");

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
