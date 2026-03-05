<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class SistemaSugerirCompras extends Command
{

    protected $signature = 'sistema:sugerir-compras';

    protected $description = 'Genera sugerencias automáticas de compra según stock mínimo';


    public function handle()
    {

        $this->info('');
        $this->info('======================================');
        $this->info(' SUGERENCIAS AUTOMÁTICAS DE COMPRA');
        $this->info('======================================');
        $this->info('');

        $productos = Product::whereColumn('stock','<=','stock_min')
            ->orderBy('stock')
            ->get();

        if($productos->isEmpty()){

            $this->info('✔ No hay productos que necesiten reposición');
            return Command::SUCCESS;

        }

        $this->table(
            ['Producto','Stock actual','Stock mínimo','Compra sugerida'],
            $productos->map(function($p){

                $sugerido = max($p->stock_ideal - $p->stock,1);

                return [
                    $p->name,
                    $p->stock,
                    $p->stock_min,
                    $sugerido
                ];

            })
        );

        $this->info('');
        $this->info('✔ Sugerencias generadas correctamente');
        $this->info('');

        return Command::SUCCESS;

    }

}
