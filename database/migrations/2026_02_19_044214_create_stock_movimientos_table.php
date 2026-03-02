<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_movimientos', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELACIONES
            |--------------------------------------------------------------------------
            */

            // Empresa dueña del movimiento
            $table->unsignedBigInteger('empresa_id');

            // Producto afectado
            $table->unsignedBigInteger('product_id');

            /*
            |--------------------------------------------------------------------------
            | TIPO DE MOVIMIENTO
            |--------------------------------------------------------------------------
            | venta
            | compra
            | ajuste
            | devolucion_cliente
            | devolucion_proveedor
            | transferencia
            | rotura
            | inventario_inicial
            */

            $table->string('tipo', 40);

            /*
            |--------------------------------------------------------------------------
            | CANTIDAD
            |--------------------------------------------------------------------------
            | POSITIVO = ENTRA STOCK
            | NEGATIVO = SALE STOCK
            */

            $table->decimal('cantidad', 14, 3);

            /*
            |--------------------------------------------------------------------------
            | STOCK RESULTANTE (para reconstrucción histórica)
            |--------------------------------------------------------------------------
            */
            $table->decimal('stock_resultante', 14, 3)->nullable();

            /*
            |--------------------------------------------------------------------------
            | REFERENCIAS (venta, compra, ajuste, etc)
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->string('referencia_tipo')->nullable();
            // venta | compra | ajuste | proveedor | cliente

            /*
            |--------------------------------------------------------------------------
            | DETALLE / OBSERVACIÓN
            |--------------------------------------------------------------------------
            */
            $table->text('detalle')->nullable();

            /*
            |--------------------------------------------------------------------------
            | USUARIO QUE REALIZÓ EL MOVIMIENTO
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('user_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ÍNDICES (MUY IMPORTANTES PARA VELOCIDAD)
            |--------------------------------------------------------------------------
            */
            $table->index(['empresa_id', 'product_id']);
            $table->index('tipo');
            $table->index('referencia_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movimientos');
    }
};
