<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresa_config', function (Blueprint $table) {
            if (!Schema::hasColumn('empresa_config', 'mod_campos_dinamicos')) {
                $table->boolean('mod_campos_dinamicos')->default(false)->after('mod_internaciones');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_config', function (Blueprint $table) {
            if (Schema::hasColumn('empresa_config', 'mod_campos_dinamicos')) {
                $table->dropColumn('mod_campos_dinamicos');
            }
        });
    }
};
