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
        Schema::table('camas', function (Blueprint $table) {
            $table->string('qr_token', 60)->nullable()->unique()->after('estado');
        });

        Schema::create('bed_presence_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->index();
            $table->unsignedBigInteger('bed_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('internacion_id')->index();
            $table->timestamp('scanned_at');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('bed_id')->references('id')->on('camas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_presence_logs');

        Schema::table('camas', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
