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
        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'phone')) {
                $table->string('phone')->nullable()->after('name');
            }
            if (!Schema::hasColumn('suppliers', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('suppliers', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
