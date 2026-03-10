<?php
// Script temporal para auditar la salud de la BD en Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tablesData = DB::select("SELECT table_name as table_name FROM information_schema.tables WHERE table_schema = DATABASE()");
$tables = array_map(function($t) { return $t->table_name; }, $tablesData);
$report = [];

foreach ($tables as $table) {
    if (in_array($table, ['migrations', 'failed_jobs', 'password_resets', 'password_reset_tokens', 'personal_access_tokens', 'sessions', 'cache', 'cache_locks'])) {
        continue;
    }
    
    $count = DB::table($table)->count();
    $report[] = [
        'table' => $table,
        'rows' => $count
    ];
}

$output = "=== REPORTE DE SALUD DE BASE DE DATOS ===\n\n";

$output .= "TABLAS VACÍAS (Potencialmente sin uso o limpias):\n";
foreach ($report as $data) {
    if ($data['rows'] === 0) {
        $output .= "- " . $data['table'] . "\n";
    }
}

$output .= "\nTABLAS CON DATOS ACTIVOS:\n";
foreach ($report as $data) {
    if ($data['rows'] > 0) {
        $output .= "- " . $data['table'] . " (" . $data['rows'] . " registros)\n";
    }
}

file_put_contents(__DIR__ . '/tmp_db_report_phponly.txt', $output);
echo "Reporte guardado en tmp_db_report_phponly.txt";
