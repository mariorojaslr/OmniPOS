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
        Schema::table('product_videos', function (Blueprint $table) {
            $table->string('youtube_url')->nullable()->change();
            $table->string('bunny_video_id')->nullable()->after('youtube_url');
            $table->string('bunny_library_id')->nullable()->after('bunny_video_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_videos', function (Blueprint $table) {
            $table->string('youtube_url')->nullable(false)->change();
            $table->dropColumn(['bunny_video_id', 'bunny_library_id']);
        });
    }
};
