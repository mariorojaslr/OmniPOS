<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('total_sin_iva', 12, 2)->after('user_id');
            $table->decimal('total_iva', 12, 2)->after('total_sin_iva');
            $table->decimal('total_con_iva', 12, 2)->after('total_iva');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'total_sin_iva',
                'total_iva',
                'total_con_iva',
            ]);
        });
    }
};
