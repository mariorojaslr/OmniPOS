<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Muestra la Bóveda de Resguardo de la Empresa.
     */
    public function index()
    {
        $empresa = Auth::user()->empresa;
        return view('empresa.backup.index', compact('empresa'));
    }

    /**
     * Genera y descarga un resguardo de datos según el tipo.
     */
    public function download(Request $request)
    {
        $empresa = Auth::user()->empresa;
        $type = $request->query('type', 'sql');

        $tables = [
            'products'          => 'Artículos y Stock',
            'product_variants'  => 'Variantes de Productos',
            'ventas'            => 'Historial de Ventas',
            'venta_items'       => 'Detalle de Ventas',
            'expenses'          => 'Gastos y Egresos',
            'clients'           => 'Cartera de Clientes',
            'suppliers'         => 'Proveedores',
            'finanzas_cuentas'  => 'Cuentas Bancarias/Cajas',
            'finanzas_movimientos' => 'Movimientos de Tesorería',
            'asistencias'       => 'Reloj de Personal',
            'purchases'         => 'Compras a Proveedores',
            'client_ledgers'    => 'Cuentas Corrientes Clientes',
            'supplier_ledgers'  => 'Cuentas Corrientes Proveedores'
        ];

        try {
            if ($type === 'sql') {
                return $this->exportToSql($empresa, $tables);
            } elseif ($type === 'csv') {
                return $this->exportToCsv($empresa, $tables);
            } elseif ($type === 'media') {
                return $this->exportMedia($empresa);
            } elseif ($type === 'tokens') {
                return $this->exportTokens($empresa);
            }
        } catch (Exception $e) {
            Log::error("Error en backup ({$type}): " . $e->getMessage());
            return response()->json(['error' => 'Error al generar el resguardo: ' . $e->getMessage()], 500);
        }

        return response()->json(['error' => 'Tipo de resguardo no soportado.'], 400);
    }

    /**
     * Genera un volcado SQL profesional con sentencias INSERT.
     */
    private function exportToSql($empresa, $tables)
    {
        $fileName = 'backup_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.sql';

        return new StreamedResponse(function () use ($empresa, $tables) {
            $handle = fopen('php://output', 'w');
            
            fwrite($handle, "-- MULTIPOS SQL BACKUP\n");
            fwrite($handle, "-- Empresa: {$empresa->nombre_comercial}\n");
            fwrite($handle, "-- Fecha: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- ------------------------------------------------------\n\n");
            fwrite($handle, "SET NAMES utf8mb4;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n\n");

            foreach ($tables as $tableName => $label) {
                if (!Schema::hasTable($tableName)) continue;

                fwrite($handle, "--\n-- Table structure for table `{$tableName}` ({$label})\n--\n\n");
                
                $columns = Schema::getColumnListing($tableName);
                $query = DB::table($tableName);
                if (in_array('empresa_id', $columns)) {
                    $query->where('empresa_id', $empresa->id);
                }

                $query->lazy()->each(function ($row) use ($handle, $tableName, $columns) {
                    $values = [];
                    foreach ($columns as $column) {
                        $val = $row->{$column};
                        if (is_null($val)) {
                            $values[] = 'NULL';
                        } elseif (is_numeric($val) && !in_array($column, ['phone', 'cuit', 'dni', 'cae'])) {
                            $values[] = $val;
                        } else {
                            $values[] = "'" . addslashes($val) . "'";
                        }
                    }
                    $sql = "INSERT INTO `{$tableName}` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $values) . ");\n";
                    fwrite($handle, $sql);
                });

                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function exportToCsv($empresa, $tables)
    {
        $fileName = 'backup_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.csv';

        return new StreamedResponse(function () use ($empresa, $tables) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM

            foreach ($tables as $tableName => $label) {
                if (!Schema::hasTable($tableName)) continue;

                fputcsv($handle, ["--- SECCIÓN: $label ---"]);
                $columns = Schema::getColumnListing($tableName);
                fputcsv($handle, $columns);

                $query = DB::table($tableName);
                if (in_array('empresa_id', $columns)) {
                    $query->where('empresa_id', $empresa->id);
                }

                foreach ($query->lazy() as $row) {
                    $data = [];
                    foreach ($columns as $column) {
                        $data[] = $row->{$column} ?? '';
                    }
                    fputcsv($handle, $data);
                }
                fputcsv($handle, []);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Empaqueta archivos multimedia locales (logos, comprobantes locales) en un ZIP.
     */
    private function exportMedia($empresa)
    {
        // Nota: Bunny.net no se puede respaldar así, esto es para archivos locales de storage/app/public
        $zip = new \ZipArchive();
        $fileName = 'media_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.zip';
        $tempFile = tempnam(sys_get_temp_dir(), 'zip');

        if ($zip->open($tempFile, \ZipArchive::CREATE) === TRUE) {
            // Intentar respaldar logo si existe
            if ($empresa->logo_url && file_exists(public_path($empresa->logo_url))) {
                $zip->addFile(public_path($empresa->logo_url), 'logo_' . basename($empresa->logo_url));
            }

            // Comprobantes en storage/app/public/comprobantes/empresa_X
            $path = storage_path("app/public/comprobantes/empresa_{$empresa->id}");
            if (is_dir($path)) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($path) + 1);
                        $zip->addFile($filePath, "comprobantes/" . $relativePath);
                    }
                }
            }
            
            $zip->close();
        }

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Resguardo de certificados AFIP y tokens de integración.
     */
    private function exportTokens($empresa)
    {
        $fileName = 'config_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.txt';
        
        $content = "MULTIPOS - CONFIGURACION Y TOKENS\n";
        $content .= "Empresa: {$empresa->nombre_comercial}\n";
        $content .= "CUIT: {$empresa->cuit}\n";
        $content .= "--------------------------------------------------\n\n";
        $content .= "Punto de Venta: " . ($empresa->punto_venta ?? 'No definido') . "\n";
        $content .= "Certificado AFIP: " . ($empresa->cert_path ? 'PRESENTE (' . basename($empresa->cert_path) . ')' : 'NO CARGADO') . "\n";
        $content .= "Key AFIP: " . ($empresa->key_path ? 'PRESENTE (' . basename($empresa->key_path) . ')' : 'NO CARGADO') . "\n";
        $content .= "Modo AFIP: " . ($empresa->afip_mod ? 'Producción' : 'Testing') . "\n";
        
        return response($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
