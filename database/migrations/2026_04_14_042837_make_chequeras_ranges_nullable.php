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
        Schema::table('chequeras', function (Blueprint $table) {
            $table->integer('desde')->nullable()->change();
            $table->integer('hasta')->nullable()->change();
            $table->integer('proximo_numero')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chequeras', function (Blueprint $table) {
            $table->integer('desde')->nullable(false)->change();
            $table->integer('hasta')->nullable(false)->change();
            $table->integer('proximo_numero')->nullable(false)->change();
        });
    }
};
