<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. CUENTAS FINANCIERAS (Cajas, Bancos, Billeteras)
        Schema::create('finanzas_cuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombre');          // Ej: Fondo Fijo, Banco Galicia, Mercado Pago
            $table->string('tipo');            // 'caja', 'banco', 'billetera_digital', 'tarjeta_credito'
            $table->string('moneda')->default('ARS');
            $table->string('numero_cuenta')->nullable();
            $table->string('cbu_cvu')->nullable();
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->decimal('saldo_actual', 15, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('empresa_id');
        });

        // 2. MOVIMIENTOS DE TESORERIA (Libro Diario Unificado)
        Schema::create('finanzas_movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('cuenta_id');
            $table->unsignedBigInteger('user_id');
            
            $table->enum('tipo', ['ingreso', 'egreso', 'transferencia']);
            $table->decimal('monto', 15, 2);
            $table->date('fecha');
            
            $table->string('concepto');
            $table->string('categoria')->nullable(); // Ej: Venta, Sueldo, Impuestos
            
            // Referencias polimórficas (Opcional para trazabilidad)
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->boolean('conciliado')->default(false);
            $table->string('comprobante')->nullable(); // Link a imagen o PDF
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'cuenta_id']);
            $table->index(['reference_type', 'reference_id']);
            
            $table->foreign('cuenta_id')->references('id')->on('finanzas_cuentas')->onDelete('cascade');
        });

        // 3. Vincular Cheques con las cuentas (opcional para conciliación)
        if (Schema::hasTable('cheques')) {
            Schema::table('cheques', function (Blueprint $table) {
                if (!Schema::hasColumn('cheques', 'cuenta_id')) {
                    $table->unsignedBigInteger('cuenta_id')->nullable()->after('chequera_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cheques')) {
            Schema::table('cheques', function (Blueprint $table) {
                if (Schema::hasColumn('cheques', 'cuenta_id')) {
                    $table->dropColumn('cuenta_id');
                }
            });
        }
        Schema::dropIfExists('finanzas_movimientos');
        Schema::dropIfExists('finanzas_cuentas');
    }
};
