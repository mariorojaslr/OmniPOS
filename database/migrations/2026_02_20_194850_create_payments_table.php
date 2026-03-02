<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {

            Schema::create('payments', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('empresa_id');
                $table->unsignedBigInteger('client_id')->nullable();

                $table->decimal('amount', 15, 2);

                $table->enum('method', [
                    'efectivo',
                    'transferencia',
                    'mercadopago',
                    'tarjeta',
                    'otro'
                ]);

                $table->string('reference')->nullable();

                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
