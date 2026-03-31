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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('slug')->unique()->after('nombre_comercial')->nullable();
        });

        // Autorellenar slugs para empresas existentes
        foreach (\App\Models\Empresa::all() as $empresa) {
            $empresa->slug = \Illuminate\Support\Str::slug($empresa->nombre_comercial) . '-' . $empresa->id;
            $empresa->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
