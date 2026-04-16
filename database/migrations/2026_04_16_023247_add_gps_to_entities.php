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
        if (!Schema::hasColumn('clients', 'lat')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('lat')->nullable()->after('address');
                $table->string('lng')->nullable()->after('lat');
            });
        }

        if (!Schema::hasColumn('suppliers', 'lat')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->string('lat')->nullable()->after('direccion');
                $table->string('lng')->nullable()->after('lat');
            });
        }
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
        });
    }
};
