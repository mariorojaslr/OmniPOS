<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\VentaItem;
use Illuminate\Support\Facades\DB;

class RecalcularTotalesVentas extends Command
{
    protected $signature   = 'ventas:recalcular-totales';
    protected $description = 'Recalcula total_con_iva de todas las ventas cuyos totales quedaron en $0 (ventas históricas antiguas)';

    public function handle()
    {
        $this->info('Buscando ventas con total_con_iva = 0...');

        $ventasConProblema = Venta::where('total_con_iva', 0)
            ->with('items')
            ->get();

        if ($ventasConProblema->isEmpty()) {
            $this->info('No se encontraron ventas con total en $0,00. ¡Todo está bien!');
            return 0;
        }

        $this->info("Se encontraron {$ventasConProblema->count()} ventas con total $0. Recalculando...");
        $bar = $this->output->createProgressBar($ventasConProblema->count());
        $bar->start();

        $arregladas = 0;

        foreach ($ventasConProblema as $venta) {
            $items = $venta->items;

            if ($items->isEmpty()) {
                $bar->advance();
                continue;
            }

            $totalConIva = 0;
            $totalSinIva = 0;
            $totalIva    = 0;

            foreach ($items as $item) {
                $totalConIva += $item->total_item_con_iva;
                $totalSinIva += $item->subtotal_item_sin_iva;
                $totalIva    += $item->iva_item;
            }

            if ($totalConIva > 0) {
                $venta->update([
                    'total_con_iva' => $totalConIva,
                    'total_sin_iva' => $totalSinIva,
                    'total_iva'     => $totalIva,
                ]);
                $arregladas++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Completado: {$arregladas} ventas recalculadas correctamente.");

        return 0;
    }
}
