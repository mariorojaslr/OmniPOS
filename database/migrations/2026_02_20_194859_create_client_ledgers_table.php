<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('client_ledgers')) {

            Schema::create('client_ledgers', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('empresa_id');
                $table->unsignedBigInteger('client_id');

                $table->enum('type', ['debit', 'credit']);
                $table->decimal('amount', 15, 2);

                $table->string('description')->nullable();
                $table->date('due_date')->nullable();

                $table->boolean('paid')->default(false);

                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_ledgers');
    }
};
