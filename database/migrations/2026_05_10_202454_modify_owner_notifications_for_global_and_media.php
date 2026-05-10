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
        Schema::table('owner_notifications', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->change();
            $table->string('title')->nullable()->after('empresa_id');
            $table->string('media_url')->nullable()->after('message');
            $table->string('media_type')->nullable()->after('media_url'); // 'image', 'video'
        });
    }

    public function down(): void
    {
        Schema::table('owner_notifications', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable(false)->change();
            $table->dropColumn(['title', 'media_url', 'media_type']);
        });
    }
};
