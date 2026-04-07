<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run()
    {
        // Limpiamos estándar previo para evitar duplicados
        DB::table('units')->whereNull('empresa_id')->delete();

        $units = [
            // PESO
            ['name' => 'KILOGRAMO', 'short_name' => 'KG', 'empresa_id' => null, 'active' => 1],
            ['name' => 'GRAMO', 'short_name' => 'G', 'empresa_id' => null, 'active' => 1],
            ['name' => 'MILIGRAMO', 'short_name' => 'MG', 'empresa_id' => null, 'active' => 1],
            
            // VOLUMEN
            ['name' => 'LITRO', 'short_name' => 'L', 'empresa_id' => null, 'active' => 1],
            ['name' => 'MILILITRO', 'short_name' => 'ML', 'empresa_id' => null, 'active' => 1],
            ['name' => 'CENTIMETROS CÚBICOS', 'short_name' => 'CC', 'empresa_id' => null, 'active' => 1],
            
            // CANTIDAD
            ['name' => 'UNIDAD', 'short_name' => 'U', 'empresa_id' => null, 'active' => 1],
            ['name' => 'DOCENA', 'short_name' => 'DOC', 'empresa_id' => null, 'active' => 1],
            ['name' => 'MEDIA DOCENA', 'short_name' => '1/2 DOC', 'empresa_id' => null, 'active' => 1],
            
            // PACKAGING / AGRUPADORES
            ['name' => 'PAQUETE', 'short_name' => 'PQTE', 'empresa_id' => null, 'active' => 1],
            ['name' => 'PACK X6', 'short_name' => 'PK6', 'empresa_id' => null, 'active' => 1],
            ['name' => 'PACK X12', 'short_name' => 'PK12', 'empresa_id' => null, 'active' => 1],
            ['name' => 'CAJA', 'short_name' => 'CAJA', 'empresa_id' => null, 'active' => 1],
            ['name' => 'BOLSA', 'short_name' => 'BOLSA', 'empresa_id' => null, 'active' => 1],
            ['name' => 'ATADO', 'short_name' => 'ATADO', 'empresa_id' => null, 'active' => 1],
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert(array_merge($unit, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
