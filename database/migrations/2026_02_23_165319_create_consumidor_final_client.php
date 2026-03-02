<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear cliente CONSUMIDOR FINAL en todas las empresas
     */
    public function up(): void
    {
        $empresas = DB::table('empresas')->get();

        foreach ($empresas as $empresa) {

            $existe = DB::table('clients')
                ->where('empresa_id', $empresa->id)
                ->where('document', 'CF')
                ->exists();

            if (!$existe) {

                DB::table('clients')->insert([
                    'empresa_id' => $empresa->id,
                    'name' => 'CONSUMIDOR FINAL',
                    'document' => 'CF',
                    'type' => 'consumidor_final',
                    'tax_condition' => 'Consumidor Final',
                    'credit_limit' => 0,
                    'discount_percentage' => 0,
                    'active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * No se elimina en rollback (cliente estructural del sistema)
     */
    public function down(): void
    {
        // No borrar: cliente obligatorio del sistema
    }
};
