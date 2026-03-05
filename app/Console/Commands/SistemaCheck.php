<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\KardexMovimiento;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class SistemaCheck extends Command
{

    /*
    |--------------------------------------------------------------------------
    | COMANDO
    |--------------------------------------------------------------------------
    */

    protected $signature = 'sistema:check';

    protected $description = 'Auditoría completa del sistema (inventario, kardex, ventas y compras)';


    /*
    |--------------------------------------------------------------------------
    | EJECUCIÓN
    |--------------------------------------------------------------------------
    */

    public function handle()
    {

        $this->info('');
        $this->info('======================================');
        $this->info(' AUDITORÍA DEL SISTEMA MULTIPOS');
        $this->info('======================================');
        $this->info('');


        /*
        |--------------------------------------------------------------------------
        | INVENTARIO
        |--------------------------------------------------------------------------
        */

        $this->info('Revisando inventario...');

        $productosNegativos = Product::where('stock','<',0)->count();

        if($productosNegativos > 0){
            $this->error("⚠ Hay {$productosNegativos} productos con stock negativo");
        } else {
            $this->info("✔ Inventario sin stock negativo");
        }


        $productosSinEmpresa = Product::whereNull('empresa_id')->count();

        if($productosSinEmpresa > 0){
            $this->error("⚠ Hay {$productosSinEmpresa} productos sin empresa");
        } else {
            $this->info("✔ Todos los productos tienen empresa");
        }


        $stockBajo = Product::whereColumn('stock','<=','stock_min')->count();

        if($stockBajo > 0){
            $this->warn("⚠ {$stockBajo} productos bajo stock mínimo");
        } else {
            $this->info("✔ Ningún producto bajo mínimo");
        }

        $this->newLine();



        /*
        |--------------------------------------------------------------------------
        | KARDEX
        |--------------------------------------------------------------------------
        */

        $this->info('Revisando consistencia de Kardex...');

        $erroresKardex = 0;
        $productosSinMovimiento = 0;

        $productos = Product::all();

        foreach($productos as $producto){

            $ultimo = KardexMovimiento::where('product_id',$producto->id)
                ->latest()
                ->first();

            if(!$ultimo){
                $productosSinMovimiento++;
                continue;
            }

            if($ultimo->stock_resultante != $producto->stock){
                $erroresKardex++;
            }

        }

        if($erroresKardex > 0){
            $this->error("⚠ {$erroresKardex} productos con kardex inconsistente");
        } else {
            $this->info("✔ Kardex consistente con inventario");
        }

        if($productosSinMovimiento > 0){
            $this->warn("⚠ {$productosSinMovimiento} productos sin movimientos en kardex");
        }

        $this->newLine();



        /*
        |--------------------------------------------------------------------------
        | VENTAS
        |--------------------------------------------------------------------------
        */

        $this->info('Revisando ventas...');

        $ventas = Venta::count();

        $ventasSinItems = Venta::doesntHave('items')->count();

        if($ventasSinItems > 0){
            $this->error("⚠ {$ventasSinItems} ventas sin items");
        } else {
            $this->info("✔ Todas las ventas tienen items");
        }


        $ventasIncorrectas = 0;

        $ventasLista = Venta::with('items')->get();

        foreach($ventasLista as $venta){

            $suma = $venta->items->sum('total_item_con_iva');

            if(abs($suma - $venta->total_con_iva) > 0.01){
                $ventasIncorrectas++;
            }

        }

        if($ventasIncorrectas > 0){
            $this->error("⚠ {$ventasIncorrectas} ventas con total incorrecto");
        } else {
            $this->info("✔ Totales de ventas correctos");
        }

        $this->info("Ventas registradas: {$ventas}");

        $this->newLine();



        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS NUNCA VENDIDOS
        |--------------------------------------------------------------------------
        */

        $productosNuncaVendidos = Product::doesntHave('ventaItems')->count();

        if($productosNuncaVendidos > 0){
            $this->warn("⚠ {$productosNuncaVendidos} productos nunca vendidos");
        } else {
            $this->info("✔ Todos los productos tuvieron ventas");
        }

        $this->newLine();



        /*
        |--------------------------------------------------------------------------
        | COMPRAS
        |--------------------------------------------------------------------------
        */

        if(class_exists(Purchase::class)){

            $this->info('Revisando compras...');

            $compras = Purchase::count();

            $comprasSinItems = Purchase::doesntHave('items')->count();

            if($comprasSinItems > 0){
                $this->error("⚠ {$comprasSinItems} compras sin items");
            } else {
                $this->info("✔ Todas las compras tienen items");
            }

            $this->info("Compras registradas: {$compras}");

            $this->newLine();

        }



        /*
        |--------------------------------------------------------------------------
        | RESULTADO FINAL
        |--------------------------------------------------------------------------
        */

        $this->info('======================================');
        $this->info(' AUDITORÍA COMPLETADA');
        $this->info('======================================');
        $this->info('');

        return Command::SUCCESS;

    }

}
