<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use Illuminate\Support\Facades\Schema;

class ClearEmpresaDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'empresa:reset {id : ID de la empresa a limpiar} {--all : ¿Borrar también productos y categorías?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todos los datos de transacción (ventas, stock, cajas) de una empresa para reiniciarla tras pruebas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $empresa = Empresa::find($id);

        if (!$empresa) {
            $this->error("No se encontró la empresa con ID: {$id}");
            return Command::FAILURE;
        }

        $this->info("Iniciando purga de datos para: {$empresa->nombre_comercial} (ID: {$id})");

        if (!$this->confirm("¿Seguro que quieres borrar TODAS las ventas, movimientos de stock, gastos y auditorías de esta empresa? ESTO NO SE PUEDE DESHACER.")) {
            $this->info("Operación cancelada.");
            return Command::SUCCESS;
        }

        DB::transaction(function () use ($id) {
            // 1. Borrar Ventas/VentasItems
            if (Schema::hasTable('venta_items')) {
                DB::table('venta_items')->whereIn('venta_id', function($q) use ($id) {
                    $q->select('id')->from('ventas')->where('empresa_id', $id);
                })->delete();
                DB::table('ventas')->where('empresa_id', $id)->delete();
                $this->info("Ventas eliminadas.");
            }

            // 2. Borrar Cierres de Cajas
            if (Schema::hasTable('cajas')) {
                DB::table('cajas')->where('empresa_id', $id)->delete();
                $this->info("Cajas eliminadas.");
            }

            // 3. Borrar Movimientos (Kardex)
            if (Schema::hasTable('kardex_movimientos')) {
                DB::table('kardex_movimientos')->where('empresa_id', $id)->delete();
                $this->info("Movimientos de Kardex eliminados.");
            }
            
            DB::table('inventory_sessions')->where('empresa_id', $id)->delete();
            $this->info("Sesiones de inventario eliminadas.");

            // 4. Borrar Gastos
            if (Schema::hasTable('expenses')) {
                DB::table('expenses')->where('empresa_id', $id)->delete();
                $this->info("Gastos eliminados.");
            }

            // 5. Borrar Compras y sus detalles
            if (Schema::hasTable('purchase_items')) {
                DB::table('purchase_items')->whereIn('purchase_id', function($q) use ($id) {
                    $q->select('id')->from('purchases')->where('empresa_id', $id);
                })->delete();
                DB::table('purchases')->where('empresa_id', $id)->delete();
                $this->info("Compras eliminadas.");
            }

            // 6. Borrar Historial de Cuentas Corrientes (Saldos)
            if (Schema::hasTable('client_ledgers')) {
                DB::table('client_ledgers')->whereIn('client_id', function($q) use ($id) {
                    $q->select('id')->from('clients')->where('empresa_id', $id);
                })->delete();
                $this->info("Libros de cuentas de clientes limpiados.");
            }
            if (Schema::hasTable('supplier_ledgers')) {
                DB::table('supplier_ledgers')->whereIn('supplier_id', function($q) use ($id) {
                    $q->select('id')->from('suppliers')->where('empresa_id', $id);
                })->delete();
                $this->info("Libros de cuentas de proveedores limpiados.");
            }

            // 7. Borrar Clientes
            if (Schema::hasTable('clients')) {
                DB::table('clients')->where('empresa_id', $id)->delete();
                $this->info("Clientes eliminados.");
            }

            // 6. Si se solicita --all, borrar catálogo maestro
            if ($this->option('all')) {
                DB::table('product_variants')->whereIn('product_id', function($q) use ($id) {
                    $q->select('id')->from('products')->where('empresa_id', $id);
                })->delete();
                DB::table('products')->where('empresa_id', $id)->delete();
                DB::table('rubros')->where('empresa_id', $id)->delete();
                $this->warn("Catálogo completo (Productos y Rubros) eliminado.");
            } else {
                // RESETEAR STOCK A 0 en productos y variantes existentes
                DB::table('products')->where('empresa_id', $id)->update(['stock' => 0]);
                DB::table('product_variants')->whereIn('product_id', function($q) use ($id) {
                    $q->select('id')->from('products')->where('empresa_id', $id);
                })->update(['stock' => 0]);
                $this->info("Stocks de productos reseteados a 0.");
            }
        });

        $this->info("¡Limpieza completada con éxito para '{$empresa->nombre_comercial}'!");
        return Command::SUCCESS;
    }
}
