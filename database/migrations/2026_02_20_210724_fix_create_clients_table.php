<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('clients')) {

            Schema::create('clients', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('empresa_id');

                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();

                $table->string('document')->nullable();
                $table->string('tax_condition')->nullable();

                $table->enum('type', [
                    'consumidor_final',
                    'minorista',
                    'mayorista',
                    'revendedor',
                    'amigo'
                ])->default('consumidor_final');

                $table->decimal('discount_percentage', 5, 2)->default(0);
                $table->decimal('credit_limit', 15, 2)->default(0);

                $table->boolean('active')->default(true);

                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
