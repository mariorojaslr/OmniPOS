<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Empresa;
use App\Models\Rubro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StressTestProducts extends Command
{
    /**
     * El nombre y firma del comando.
     * @var string
     */
    protected $signature = 'system:stress-test 
                            {empresa_id : El ID de la empresa para el test} 
                            {--count=10000 : Cantidad de productos a generar} 
                            {--cleanup : Si se activa, borrará los productos de stress test previos}';

    /**
     * La descripción del comando.
     * @var string
     */
    protected $description = 'Inyecta miles de productos en una empresa para probar el rendimiento del sistema (Stress Test).';

    /**
     * Ejecutar el comando.
     */
    public function handle()
    {
        $empresaId = $this->argument('empresa_id');
        $count = $this->option('count');
        $empresa = Empresa::find($empresaId);

        if (!$empresa) {
            $this->error("Empresa ID {$empresaId} no encontrada.");
            return;
        }

        // 1. LIMPIEZA (Si se solicita)
        if ($this->option('cleanup')) {
            $this->info("Limpiando productos de stress test previos para la empresa {$empresa->nombre_comercial}...");
            Product::where('empresa_id', $empresaId)
                   ->where('name', 'LIKE', 'STRESS-TEST-%')
                   ->delete();
            $this->info("Limpieza completada.");
            return;
        }

        $this->info("Iniciando Stress Test para: {$empresa->nombre_comercial}");
        $this->info("Objetivo: Generar {$count} productos.");

        // Aseguramos que exista al menos un rubro
        $rubro = Rubro::where('empresa_id', $empresaId)->first();
        if (!$rubro) {
            $rubro = Rubro::create(['empresa_id' => $empresaId, 'nombre' => 'Categoría Test']);
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $chunkSize = 1000;
        $now = now();

        for ($i = 0; $i < $count; $i += $chunkSize) {
            $data = [];
            $currentChunk = min($chunkSize, $count - $i);

            for ($j = 0; $j < $currentChunk; $j++) {
                $uniqueId = Str::random(8);
                $data[] = [
                    'empresa_id'        => $empresaId,
                    'name'              => "STRESS-TEST-PRODUCT-{$uniqueId}",
                    'sku'               => "SKU-ST-{$uniqueId}",
                    'price'             => rand(100, 50000),
                    'cost'              => rand(50, 25000),
                    'stock'             => rand(0, 500),
                    'stock_min'         => 5,
                    'stock_ideal'       => 50,
                    'active'            => true,
                    'is_sellable'       => true,
                    'usage_type'        => 'sell',
                    'rubro_id'          => $rubro->id,
                    'descripcion_corta' => "Descripción automática para stress test del producto {$uniqueId}",
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }

            DB::table('products')->insert($data);
            $bar->advance($currentChunk);
        }

        $bar->finish();
        $this->newLine();
        $this->info("¡Éxito! Se han inyectado {$count} productos.");
        $this->info("Ahora puedes entrar al Catalogo o POS de la empresa {$empresaId} y probar la velocidad.");
    }
}
