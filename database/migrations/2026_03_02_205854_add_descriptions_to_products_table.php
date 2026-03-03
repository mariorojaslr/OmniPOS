<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('descripcion_corta')->nullable()->after('name');
            $table->longText('descripcion_larga')->nullable()->after('descripcion_corta');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'descripcion_corta',
                'descripcion_larga'
            ]);
        });
    }
};
