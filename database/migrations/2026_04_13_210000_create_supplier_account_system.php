<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Mejorar SupplierLedger con polimorfismo y saldo restante
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_ledgers', 'reference_type')) {
                $table->string('reference_type')->nullable()->after('supplier_id');
            }
            if (!Schema::hasColumn('supplier_ledgers', 'reference_id')) {
                $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            }
            if (!Schema::hasColumn('supplier_ledgers', 'pending_amount')) {
                $table->decimal('pending_amount', 15, 2)->default(0)->after('amount');
            }
            
            // Index check (simple approach)
            try {
                $table->index(['reference_type', 'reference_id'], 'supplier_ledger_ref_idx');
            } catch (\Exception $e) {}
        });

        // 2. Tabla de Cheques (para "Cheques en Cartera")
        if (!Schema::hasTable('cheques')) {
            Schema::create('cheques', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('empresa_id');
                $table->string('numero');
                $table->string('banco');
                $table->string('emisor')->nullable();
                $table->decimal('monto', 15, 2);
                $table->date('fecha_emision')->nullable();
                $table->date('fecha_pago');
                $table->enum('estado', ['en_cartera', 'depositado', 'entregado', 'rechazado', 'anulado'])->default('en_cartera');
                
                // Seguimiento de origen (quién nos lo dio)
                $table->unsignedBigInteger('client_id')->nullable(); 
                
                // Seguimiento de destino (a quién se lo dimos)
                $table->unsignedBigInteger('supplier_id')->nullable();

                $table->timestamps();
                
                $table->index(['empresa_id', 'estado']);
            });
        }

        // 3. Tabla de Ordenes de Pago (Pagos a Proveedores)
        if (!Schema::hasTable('ordenes_pago')) {
            Schema::create('ordenes_pago', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('empresa_id');
                $table->unsignedBigInteger('supplier_id');
                $table->unsignedBigInteger('user_id');
                
                $table->string('numero_orden')->unique();
                $table->decimal('monto_total', 15, 2);
                $table->date('fecha');
                $table->text('observaciones')->nullable();
                
                $table->timestamps();
                
                $table->index(['empresa_id', 'supplier_id']);
            });
        }

        // 4. Detalle de los medios de pago en la Orden de Pago
        if (!Schema::hasTable('orden_pago_pagos')) {
            Schema::create('orden_pago_pagos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('orden_pago_id')->constrained('ordenes_pago')->onDelete('cascade');
                $table->string('metodo_pago'); // efectivo, transferencia, tarjeta, cheque_propio, cheque_tercero
                $table->decimal('monto', 15, 2);
                $table->string('referencia')->nullable();
                $table->unsignedBigInteger('cheque_id')->nullable(); // Si es cheque_tercero
                $table->timestamps();
            });
        }

        // 5. Imputaciones de Orden de Pago a Compras (o deudas)
        if (!Schema::hasTable('orden_pago_imputaciones')) {
            Schema::create('orden_pago_imputaciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('orden_pago_id')->constrained('ordenes_pago')->onDelete('cascade');
                $table->foreignId('ledger_id')->constrained('supplier_ledgers')->onDelete('cascade');
                $table->decimal('monto_aplicado', 15, 2);
                $table->timestamps();
            });
        }
        
        // 6. Contador para Ordenes de Pago en Empresas
        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'proximo_numero_orden_pago')) {
                $table->integer('proximo_numero_orden_pago')->default(1);
            }
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('proximo_numero_orden_pago');
        });
        Schema::dropIfExists('orden_pago_imputaciones');
        Schema::dropIfExists('orden_pago_pagos');
        Schema::dropIfExists('ordenes_pago');
        Schema::dropIfExists('cheques');
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->dropColumn(['reference_type', 'reference_id', 'pending_amount']);
        });
    }
};
