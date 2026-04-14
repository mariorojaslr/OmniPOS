<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla de chequeras propias
        if (!Schema::hasTable('chequeras')) {
            Schema::create('chequeras', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('empresa_id');
                $table->string('banco');            // Nombre del banco
                $table->string('sucursal')->nullable();
                $table->string('numero_cuenta');     // CBU / Nro de cuenta
                $table->string('tipo_cuenta')->default('cuenta_corriente'); // cuenta_corriente, caja_ahorro
                $table->integer('desde');            // Rango de numeración: desde
                $table->integer('hasta');            // Rango de numeración: hasta
                $table->integer('proximo_numero');   // Próximo cheque a emitir
                $table->boolean('activo')->default(true);
                $table->text('notas')->nullable();
                $table->timestamps();

                $table->index('empresa_id');
            });
        }

        // Agregar campo chequera_id a la tabla cheques para vincular cheques propios
        if (Schema::hasTable('cheques')) {
            Schema::table('cheques', function (Blueprint $table) {
                if (!Schema::hasColumn('cheques', 'chequera_id')) {
                    $table->unsignedBigInteger('chequera_id')->nullable()->after('empresa_id');
                }
                if (!Schema::hasColumn('cheques', 'tipo')) {
                    $table->enum('tipo', ['tercero', 'propio'])->default('tercero')->after('estado');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cheques')) {
            Schema::table('cheques', function (Blueprint $table) {
                if (Schema::hasColumn('cheques', 'chequera_id')) {
                    $table->dropColumn('chequera_id');
                }
                if (Schema::hasColumn('cheques', 'tipo')) {
                    $table->dropColumn('tipo');
                }
            });
        }
        Schema::dropIfExists('chequeras');
    }
};
