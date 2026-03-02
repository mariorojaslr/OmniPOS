<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_ledgers', function (Blueprint $table) {

            $table->index(['empresa_id', 'client_id']);
            $table->index(['client_id', 'created_at']);
            $table->index('type');
            $table->index('created_at');

        });
    }

    public function down(): void
    {
        Schema::table('client_ledgers', function (Blueprint $table) {

            $table->dropIndex(['empresa_id', 'client_id']);
            $table->dropIndex(['client_id', 'created_at']);
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);

        });
    }
};
