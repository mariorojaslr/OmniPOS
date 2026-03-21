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
        // Añadir campos de dirección a Clients
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable()->after('phone');
                $table->string('city')->nullable()->after('address');
                $table->string('province')->nullable()->after('city');
            }
        });

        // Añadir client_id a Orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('empresa_id');
                $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['address', 'city', 'province']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};
