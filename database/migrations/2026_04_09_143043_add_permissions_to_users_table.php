<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'can_manage_purchases')) {
                $table->boolean('can_manage_purchases')->default(false)->after('can_register_expenses');
            }
            if (!Schema::hasColumn('users', 'can_sell')) {
                $table->boolean('can_sell')->default(true)->after('can_manage_purchases');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['can_manage_purchases', 'can_sell']);
        });
    }
};
