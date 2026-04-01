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
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('description');
            $table->foreignId('asistencia_id')->nullable()->after('user_id')->constrained('asistencias')->onDelete('set null');
            $table->string('payment_method')->default('efectivo')->after('amount'); // efectivo, cuenta_corriente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            //
        });
    }
};
