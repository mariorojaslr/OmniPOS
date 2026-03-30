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
        // 1. Cabecera del Remito
        Schema::create('remitos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('user_id'); // Quien entrega
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('numero_remito')->nullable();
            $table->timestamp('fecha_entrega')->useCurrent();
            $table->text('observaciones')->nullable();
            $table->timestamps();


            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('venta_id')->references('id')->on('ventas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        // 2. Detalle del Remito
        Schema::create('remito_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remito_id');
            $table->unsignedBigInteger('venta_item_id'); // Vínculo con la línea de la venta original
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('cantidad', 12, 2);
            $table->timestamps();

            $table->foreign('remito_id')->references('id')->on('remitos')->onDelete('cascade');
            $table->foreign('venta_item_id')->references('id')->on('venta_items')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });

        // 3. Modificar venta_items para rastrear entregas
        Schema::table('venta_items', function (Blueprint $table) {
            $table->decimal('cantidad_entregada', 12, 2)->default(0)->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta_items', function (Blueprint $table) {
            $table->dropColumn('cantidad_entregada');
        });
        Schema::dropIfExists('remito_items');
        Schema::dropIfExists('remitos');
    }
};

