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
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'plus_code')) {
                $table->string('plus_code')->nullable()->after('lng');
            }
        });

        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'plus_code')) {
                $table->string('plus_code')->nullable()->after('lng');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('plus_code');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('plus_code');
        });
    }
};
