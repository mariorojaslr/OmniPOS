<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Crear tabla de Recibos (Cobros formales)
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('user_id'); // Quien registró el cobro
            
            $table->string('numero_recibo')->unique();
            $table->decimal('monto_total', 15, 2);
            $table->string('metodo_pago'); // Efectivo, Transferencia, etc.
            $table->string('referencia')->nullable(); // Nro Transacción, etc.
            $table->date('fecha');
            
            $table->timestamps();
            
            $table->index(['empresa_id', 'client_id']);
        });

        // 2. Mejorar ClientLedger con polimorfismo y saldo restante
        Schema::table('client_ledgers', function (Blueprint $table) {
            $table->string('reference_type')->nullable()->after('client_id');
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->decimal('pending_amount', 15, 2)->default(0)->after('amount'); // Saldo que falta cobrar de este DEBIT
            
            $table->index(['reference_type', 'reference_id']);
        });
        
        // 3. Imputaciones (Historial de qué recibo pagó qué factura)
        Schema::create('recibo_imputaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recibo_id')->constrained('recibos')->onDelete('cascade');
            $table->foreignId('ledger_id')->constrained('client_ledgers')->onDelete('cascade'); // El DEBIT imputado
            $table->decimal('monto_aplicado', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recibo_imputaciones');
        Schema::dropIfExists('recibos');
        Schema::table('client_ledgers', function (Blueprint $table) {
            $table->dropColumn(['reference_type', 'reference_id', 'pending_amount']);
        });
    }
};
