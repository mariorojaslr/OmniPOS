<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chequeras', function (Blueprint $table) {
            if (!Schema::hasColumn('chequeras', 'tipo')) {
                $table->enum('tipo', ['fisica', 'echeck'])->default('fisica')->after('banco');
            }
            // Hacer opcionales desde/hasta para echecks
            $table->integer('desde')->nullable()->change();
            $table->integer('hasta')->nullable()->change();
            $table->integer('proximo_numero')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('chequeras', function (Blueprint $table) {
            if (Schema::hasColumn('chequeras', 'tipo')) {
                $table->dropColumn('tipo');
            }
        });
    }
};
