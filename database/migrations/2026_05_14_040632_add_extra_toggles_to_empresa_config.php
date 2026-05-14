<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_config', function (Blueprint $wrapper) {
            $wrapper->boolean('mod_afip')->default(false)->after('mod_turnos');
            $wrapper->boolean('mod_pagos')->default(false)->after('mod_afip');
            $wrapper->boolean('mod_backups')->default(false)->after('mod_pagos');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_config', function (Blueprint $wrapper) {
            $wrapper->dropColumn(['mod_afip', 'mod_pagos', 'mod_backups']);
        });
    }
};
