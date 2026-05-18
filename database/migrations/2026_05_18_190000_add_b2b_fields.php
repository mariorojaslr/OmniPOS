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
        Schema::table('medical_certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('b2b_convention_id')->nullable()->index()->after('user_id');
            $table->foreign('b2b_convention_id')->references('id')->on('b2b_conventions')->onDelete('set null');
        });

        Schema::table('b2b_conventions', function (Blueprint $table) {
            $table->text('servicios_requeridos')->nullable()->after('cuit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_certificates', function (Blueprint $table) {
            $table->dropForeign(['b2b_convention_id']);
            $table->dropColumn('b2b_convention_id');
        });

        Schema::table('b2b_conventions', function (Blueprint $table) {
            $table->dropColumn('servicios_requeridos');
        });
    }
};
