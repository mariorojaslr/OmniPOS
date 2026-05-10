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
        Schema::create('owner_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'vencimiento', 'aviso_general', 'deuda'
            $table->text('message');
            $table->string('channel')->default('dashboard'); // 'dashboard', 'email', 'whatsapp'
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_notifications');
    }
};
