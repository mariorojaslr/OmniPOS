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
            $table->string('photo_path')->nullable()->after('active');
            $table->string('workplace')->nullable()->after('photo_path');
            $table->foreignId('obra_social_id')->nullable()->after('workplace')->constrained('obras_sociales')->onDelete('set null');
            $table->foreignId('affiliate_plan_id')->nullable()->after('obra_social_id')->constrained('affiliate_plans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['obra_social_id']);
            $table->dropForeign(['affiliate_plan_id']);
            $table->dropColumn(['photo_path', 'workplace', 'obra_social_id', 'affiliate_plan_id']);
        });
    }
};
