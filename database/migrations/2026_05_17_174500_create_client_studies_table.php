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
        Schema::create('client_studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->index();
            $table->unsignedBigInteger('client_id')->index();
            $table->unsignedBigInteger('turno_id')->nullable()->index();
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('turno_id')->references('id')->on('turnos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_studies');
    }
};
