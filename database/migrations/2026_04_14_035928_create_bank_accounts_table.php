<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            
            // Relación polimórfica (Client o Supplier)
            $table->morphs('holder'); 
            
            $table->string('bank_name');
            $table->string('account_number')->nullable();
            $table->string('cbu_cvu')->nullable();
            $table->string('alias')->nullable();
            $table->string('account_type')->nullable(); // Corriente, Ahorro, etc.
            
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('empresa_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
