<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\Rubro;

class PopulateOperationalRubros extends Command
{
    protected $signature = 'multipos:populate-rubros';
    protected $description = 'Popula rubros operativos por defecto en todas las empresas existentes';

    public function handle()
    {
        $empresas = Empresa::all();
        $this->info("Iniciando población de rubros para " . $empresas->count() . " empresas...");

        $defaultRubros = [
            'Materia Prima',
            'Insumos de Proceso',
            'Artículos de Limpieza',
            'Packaging y Envases',
            'Papelería y Oficina',
        ];

        foreach ($empresas as $empresa) {
            $this->info("Procesando empresa: " . $empresa->nombre_comercial);
            
            foreach ($defaultRubros as $nombre) {
                // Evitamos duplicados
                Rubro::firstOrCreate([
                    'empresa_id' => $empresa->id,
                    'nombre'     => $nombre,
                ], [
                    'activo' => true
                ]);
            }
        }

        $this->info("¡Población completada con éxito!");
        return 0;
    }
}
