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
        Schema::create('system_errors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('exception_class')->nullable();
            $table->text('message')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->string('url')->nullable();
            $table->string('status')->default('pendiente'); // pendiente, revisado, resuelto
            $table->string('severity')->default('medio'); // bajo, medio, critico
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_errors');
    }
};
