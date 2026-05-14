<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Historia Clínica Electrónica
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('client_id'); // El paciente
            $table->unsignedBigInteger('user_id');   // El médico
            $table->string('specialty')->nullable();
            $table->text('reason_for_visit')->nullable();
            $table->longText('diagnosis')->nullable();
            $table->longText('treatment')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Extender Clientes para el "Plan Medplus" (Afiliados)
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'is_affiliate')) {
                $table->boolean('is_affiliate')->default(false)->after('email');
                $table->string('affiliate_number')->nullable()->after('is_affiliate');
                $table->date('affiliate_since')->nullable()->after('affiliate_number');
                $table->enum('affiliate_status', ['active', 'inactive', 'overdue'])->default('active')->after('affiliate_since');
                $table->decimal('monthly_fee', 10, 2)->default(0)->after('affiliate_status');
            }
        });

        // 3. Seguimiento de Cuotas del Plan Propio
        Schema::create('affiliate_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('client_id');
            $table->string('period'); // Ej: 2026-05
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending'); // pending, paid, overdue
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_fees');
        Schema::dropIfExists('medical_records');
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['is_affiliate', 'affiliate_number', 'affiliate_since', 'affiliate_status', 'monthly_fee']);
        });
    }
};
