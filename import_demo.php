<?php
// Script de importación robusto y seguro para el servidor de producción
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "--- 🛠️ INICIANDO CONFIGURACIÓN DE ESQUEMA ULTRA-SEGURA ---\n";

// 1. Agregar columnas necesarias si no existen (sin romper nada)
try {
    if (!Schema::hasColumn('users', 'consultorio')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('consultorio')->nullable()->after('can_sell');
        });
        echo "✅ Columna 'consultorio' añadida a 'users'.\n";
    } else {
        echo "ℹ️ Columna 'consultorio' ya existía en 'users'.\n";
    }
} catch (\Exception $e) {
    echo "⚠️ Nota 'consultorio': " . $e->getMessage() . "\n";
}

try {
    if (!Schema::hasColumn('turnos', 'hora_arribo')) {
        Schema::table('turnos', function (Blueprint $table) {
            $table->time('hora_arribo')->nullable()->after('nro_afiliado_os');
        });
        echo "✅ Columna 'hora_arribo' añadida a 'turnos'.\n";
    } else {
        echo "ℹ️ Columna 'hora_arribo' ya existía en 'turnos'.\n";
    }
} catch (\Exception $e) {
    echo "⚠️ Nota 'hora_arribo': " . $e->getMessage() . "\n";
}

try {
    if (!Schema::hasColumn('turnos', 'estado_cola')) {
        Schema::table('turnos', function (Blueprint $table) {
            $table->string('estado_cola')->nullable()->default('pendiente')->after('hora_arribo');
        });
        echo "✅ Columna 'estado_cola' añadida a 'turnos'.\n";
    } else {
        echo "ℹ️ Columna 'estado_cola' ya existía en 'turnos'.\n";
    }
} catch (\Exception $e) {
    echo "⚠️ Nota 'estado_cola': " . $e->getMessage() . "\n";
}

try {
    if (!Schema::hasColumn('clients', 'obra_social_id')) {
        Schema::table('clients', function (Blueprint $table) {
            $table->bigInteger('obra_social_id')->unsigned()->nullable()->after('workplace');
        });
        echo "✅ Columna 'obra_social_id' añadida a 'clients'.\n";
    } else {
        echo "ℹ️ Columna 'obra_social_id' ya existía en 'clients'.\n";
    }
} catch (\Exception $e) {
    echo "⚠️ Nota 'obra_social_id': " . $e->getMessage() . "\n";
}

try {
    if (!Schema::hasColumn('clients', 'additional_insurances')) {
        Schema::table('clients', function (Blueprint $table) {
            $table->json('additional_insurances')->nullable()->after('obra_social_id');
        });
        echo "✅ Columna 'additional_insurances' añadida a 'clients'.\n";
    }
} catch (\Exception $e) {
    echo "⚠️ Nota 'additional_insurances': " . $e->getMessage() . "\n";
}

try {
    if (!Schema::hasColumn('clients', 'affiliate_plan_id')) {
        Schema::table('clients', function (Blueprint $table) {
            $table->bigInteger('affiliate_plan_id')->unsigned()->nullable()->after('obra_social_id');
        });
        echo "✅ Columna 'affiliate_plan_id' añadida a 'clients'.\n";
    }
} catch (\Exception $e) {
    echo "⚠️ Nota 'affiliate_plan_id': " . $e->getMessage() . "\n";
}

echo "--- 💾 CARGANDO DATOS DE LA SUPER DEMO MÉDICA ---\n";

$sqlFile = __DIR__ . '/clinica_demo_super_seeder.sql';
if (!file_exists($sqlFile)) {
    die("❌ Error: No se encuentra el archivo 'clinica_demo_super_seeder.sql' en la raíz.\n");
}

$sqlContent = file_get_contents($sqlFile);

// Eliminar el bloque de Stored Procedure si existía para que corra directo en PDO sin errores de DELIMITER
$sqlContent = preg_replace('/DELIMITER \$\$[\s\S]*?DELIMITER ;/i', '', $sqlContent);
// Eliminar llamadas al procedimiento AddColumnSafely
$sqlContent = preg_replace('/CALL AddColumnSafely[\s\S]*?;/i', '', $sqlContent);
$sqlContent = preg_replace('/DROP PROCEDURE IF EXISTS AddColumnSafely;/i', '', $sqlContent);

try {
    DB::unprepared("SET FOREIGN_KEY_CHECKS = 0;");
    DB::unprepared($sqlContent);
    DB::unprepared("SET FOREIGN_KEY_CHECKS = 1;");
    echo "🚀 ¡IMPORTACIÓN DE DATOS COMPLETADA CON ÉXITO EN EL SERVIDOR! 🚀\n";
} catch (\Exception $e) {
    echo "❌ Error al importar los datos: " . $e->getMessage() . "\n";
}
