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
        Schema::table('users', function (Blueprint $row) {
            $row->boolean('can_manage_purchases')->default(false)->after('can_register_expenses');
            $row->boolean('can_sell')->default(false)->after('can_manage_purchases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $row) {
            $row->dropColumn(['can_manage_purchases', 'can_sell']);
        });
    }
};
