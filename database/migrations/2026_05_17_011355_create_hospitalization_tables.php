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
        // 1. Habitaciones
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombre', 100);
            $table->enum('tipo', ['comun', 'terapia_intensiva', 'terapia_intermedia', 'quirofano'])->default('comun');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });

        // 2. Camas
        Schema::create('camas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('habitacion_id');
            $table->string('nombre', 50);
            $table->enum('estado', ['libre', 'ocupada', 'mantenimiento'])->default('libre');
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('habitacion_id')->references('id')->on('habitaciones')->onDelete('cascade');
        });

        // 3. Internaciones
        Schema::create('internaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('client_id'); // El paciente/afiliado
            $table->dateTime('fecha_ingreso');
            $table->dateTime('fecha_egreso')->nullable();
            $table->enum('estado', ['activo', 'alta', 'trasladado'])->default('activo');
            $table->text('motivo_ingreso');
            $table->text('diagnostico_ingreso');
            $table->text('diagnostico_egreso')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        // 4. Asignación / Traslado de Camas
        Schema::create('internacion_camas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internacion_id');
            $table->unsignedBigInteger('cama_id');
            $table->dateTime('fecha_desde');
            $table->dateTime('fecha_hasta')->nullable();
            $table->enum('tipo_internacion', ['comun', 'terapia_intensiva', 'terapia_intermedia', 'quirofano'])->default('comun');
            $table->string('notas', 255)->nullable();
            $table->timestamps();

            $table->foreign('internacion_id')->references('id')->on('internaciones')->onDelete('cascade');
            $table->foreign('cama_id')->references('id')->on('camas')->onDelete('cascade');
        });

        // 5. Administraciones y Consumos en Internación
        Schema::create('internacion_administraciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internacion_id');
            $table->unsignedBigInteger('user_id'); // Profesional que aplica
            $table->enum('tipo', ['medicamento', 'descartable', 'control_signos', 'visita', 'otro'])->default('medicamento');
            $table->string('nombre', 150);
            $table->decimal('cantidad', 10, 2)->default(1.00);
            $table->string('dosis', 100)->nullable();
            $table->string('frecuencia', 100)->nullable();
            $table->string('via', 50)->nullable();
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->dateTime('fecha_registro');
            $table->dateTime('proxima_aplicacion')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->foreign('internacion_id')->references('id')->on('internaciones')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internacion_administraciones');
        Schema::dropIfExists('internacion_camas');
        Schema::dropIfExists('internaciones');
        Schema::dropIfExists('camas');
        Schema::dropIfExists('habitaciones');
    }
};
