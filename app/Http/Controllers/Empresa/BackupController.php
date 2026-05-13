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
            'products'             => 'Artículos y Stock',
            'product_variants'     => 'Variantes de Productos',
            'ventas'               => 'Historial de Ventas',
            'venta_items'          => 'Detalle de Ventas',
            'expenses'             => 'Gastos y Egresos',
            'clients'              => 'Cartera de Clientes',
            'suppliers'            => 'Proveedores',
            'finanzas_cuentas'     => 'Cuentas Bancarias/Cajas',
            'finanzas_movimientos' => 'Movimientos de Tesorería',
            'asistencias'          => 'Reloj de Personal',
            'purchases'            => 'Compras a Proveedores',
            'client_ledgers'       => 'Cuentas Corrientes Clientes',
            'supplier_ledgers'     => 'Cuentas Corrientes Proveedores',
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
        } catch (\Throwable $e) {
            Log::error("Error fatal en backup ({$type}): " . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['error' => 'Error crítico: ' . $e->getMessage()], 500);
        }

        return response()->json(['error' => 'Tipo de resguardo no soportado.'], 400);
    }

    // ─────────────────────────────────────────────────────────────
    //  1) RESGUARDO SQL — Genera INSERT INTO reales
    // ─────────────────────────────────────────────────────────────
    private function exportToSql($empresa, $tables)
    {
        $fileName = 'backup_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.sql';

        return new StreamedResponse(function () use ($empresa, $tables) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "-- ══════════════════════════════════════════════════\n");
            fwrite($handle, "-- MULTIPOS — RESGUARDO DE BASE DE DATOS (SQL)\n");
            fwrite($handle, "-- Empresa: {$empresa->nombre_comercial}\n");
            fwrite($handle, "-- CUIT: {$empresa->cuit}\n");
            fwrite($handle, "-- Fecha: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- ══════════════════════════════════════════════════\n\n");
            fwrite($handle, "SET NAMES utf8mb4;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n\n");

            $totalRegistros = 0;

            foreach ($tables as $tableName => $label) {
                if (!Schema::hasTable($tableName)) {
                    fwrite($handle, "-- [OMITIDA] Tabla `{$tableName}` no existe en este entorno.\n\n");
                    continue;
                }

                $columns = Schema::getColumnListing($tableName);

                $query = DB::table($tableName);
                if (in_array('empresa_id', $columns)) {
                    $query->where('empresa_id', $empresa->id);
                }

                // Usamos cursor() que funciona con CUALQUIER tabla (no requiere columna id)
                $rows = $query->cursor();
                $count = 0;

                fwrite($handle, "-- ─────────────────────────────────────────────\n");
                fwrite($handle, "-- Tabla: `{$tableName}` ({$label})\n");
                fwrite($handle, "-- ─────────────────────────────────────────────\n");

                foreach ($rows as $row) {
                    $values = [];
                    foreach ($columns as $column) {
                        $val = $row->{$column} ?? null;
                        if (is_null($val)) {
                            $values[] = 'NULL';
                        } elseif (is_array($val) || is_object($val)) {
                            $values[] = "'" . addslashes(json_encode($val)) . "'";
                        } elseif (is_numeric($val) && !in_array($column, ['phone', 'cuit', 'dni', 'cae', 'numero_comprobante'])) {
                            $values[] = $val;
                        } else {
                            $values[] = "'" . addslashes((string)$val) . "'";
                        }
                    }
                    fwrite($handle, "INSERT INTO `{$tableName}` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $values) . ");\n");
                    $count++;
                }

                fwrite($handle, "-- [{$count} registros exportados]\n\n");
                $totalRegistros += $count;
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n\n");
            fwrite($handle, "-- ══════════════════════════════════════════════════\n");
            fwrite($handle, "-- RESGUARDO FINALIZADO: {$totalRegistros} registros totales\n");
            fwrite($handle, "-- ══════════════════════════════════════════════════\n");

            fclose($handle);
        }, 200, [
            'Content-Type'        => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  2) RESGUARDO CSV — Para abrir en Excel
    // ─────────────────────────────────────────────────────────────
    private function exportToCsv($empresa, $tables)
    {
        $fileName = 'backup_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.csv';

        return new StreamedResponse(function () use ($empresa, $tables) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM para Excel

            foreach ($tables as $tableName => $label) {
                if (!Schema::hasTable($tableName)) continue;

                fputcsv($handle, ["=== SECCIÓN: {$label} ==="]);

                $columns = Schema::getColumnListing($tableName);
                fputcsv($handle, $columns);

                $query = DB::table($tableName);
                if (in_array('empresa_id', $columns)) {
                    $query->where('empresa_id', $empresa->id);
                }

                foreach ($query->cursor() as $row) {
                    $data = [];
                    foreach ($columns as $column) {
                        $val = $row->{$column} ?? '';
                        $data[] = is_array($val) || is_object($val) ? json_encode($val) : $val;
                    }
                    fputcsv($handle, $data);
                }

                fputcsv($handle, []); // Línea vacía entre tablas
            }

            fclose($handle);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  3) RESGUARDO MULTIMEDIA — Descarga imágenes desde Bunny CDN
    // ─────────────────────────────────────────────────────────────
    private function exportMedia($empresa)
    {
        $zip = new ZipArchive();
        $fileName = 'media_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.zip';
        $tempFile = tempnam(sys_get_temp_dir(), 'multipos_media_');

        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }

        $archivosAgregados = 0;
        $errores = [];
        $bunnyUrl = config('services.bunny.url') ?: 'https://gentepiola.b-cdn.net';

        // ── 1. Logo institucional (local) ──
        if ($empresa->logo_url && file_exists(public_path($empresa->logo_url))) {
            $zip->addFile(public_path($empresa->logo_url), 'logo/' . basename($empresa->logo_url));
            $archivosAgregados++;
        }

        // ── 2. Imágenes de productos desde Bunny CDN ──
        $productImages = DB::table('product_images')
            ->join('products', 'product_images.product_id', '=', 'products.id')
            ->where('products.empresa_id', $empresa->id)
            ->select('product_images.path', 'product_images.product_id', 'products.name as product_name')
            ->get();

        // Credenciales de Bunny Storage API (bypasea la protección del CDN)
        $storageHost = config('filesystems.disks.bunny_storage.host', 'ny.storage.bunnycdn.com');
        $storageZone = config('filesystems.disks.bunny_storage.username', 'gente-piola');
        $storageKey  = config('filesystems.disks.bunny_storage.password');

        foreach ($productImages as $img) {
            if (!$img->path) continue;

            // Construir URL directa a Bunny Storage API
            $storagePath = ltrim($img->path, '/');
            $url = "https://{$storageHost}/{$storageZone}/{$storagePath}";

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(15)
                    ->withHeaders(['AccessKey' => $storageKey])
                    ->get($url);

                if ($response->successful()) {
                    $safeName = Str::slug($img->product_name ?: 'producto_' . $img->product_id);
                    $zipPath = "productos/{$safeName}_" . basename($img->path);
                    $zip->addFromString($zipPath, $response->body());
                    $archivosAgregados++;
                } else {
                    $errores[] = "HTTP {$response->status()} en: {$storagePath}";
                }
            } catch (\Throwable $e) {
                $errores[] = "Error en {$storagePath}: " . $e->getMessage();
            }
        }

        // ── 3. Archivos locales (si existen) ──
        $localPaths = [
            storage_path("app/public/products/empresa_{$empresa->id}") => 'productos_local',
            storage_path("app/public/comprobantes/empresa_{$empresa->id}") => 'comprobantes',
        ];

        foreach ($localPaths as $path => $folder) {
            if (is_dir($path)) {
                $this->addDirectoryToZip($zip, $path, $folder);
                $archivosAgregados++;
            }
        }

        // ── Resumen ──
        $resumen = "RESGUARDO MULTIMEDIA - MULTIPOS\n";
        $resumen .= "Empresa: {$empresa->nombre_comercial}\n";
        $resumen .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
        $resumen .= "Archivos incluidos: {$archivosAgregados}\n";
        if (!empty($errores)) {
            $resumen .= "\nERRORES:\n" . implode("\n", $errores);
        }
        $zip->addFromString('_RESUMEN.txt', $resumen);

        $zip->close();

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Agrega recursivamente un directorio al archivo ZIP.
     */
    private function addDirectoryToZip(ZipArchive $zip, string $dirPath, string $zipFolder): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;
            $filePath = $file->getRealPath();
            $relativePath = $zipFolder . '/' . substr($filePath, strlen($dirPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  4) RESGUARDO DE CERTIFICADOS — Archivos .crt y .key reales
    // ─────────────────────────────────────────────────────────────
    private function exportTokens($empresa)
    {
        $zip = new ZipArchive();
        $fileName = 'certificados_' . Str::slug($empresa->nombre_comercial) . '_' . date('Ymd_His') . '.zip';
        $tempFile = tempnam(sys_get_temp_dir(), 'multipos_cert_');

        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }

        $archivosIncluidos = 0;

        // Buscar certificados con la MISMA lógica que usa AfipService
        $certPaths = [
            // Prioridad 1: Certificados específicos de la empresa
            'cert' => base_path("ARCA/empresa_{$empresa->id}_cert.crt"),
            'key'  => base_path("ARCA/empresa_{$empresa->id}_key.key"),
        ];

        $certGenPaths = [
            // Prioridad 2: Certificados genéricos
            'cert' => base_path('ARCA/empresa.crt'),
            'key'  => base_path('ARCA/empresa.key'),
        ];

        // Intentar certificados específicos primero
        $certFile = null;
        $keyFile = null;

        if (file_exists($certPaths['cert'])) {
            $certFile = $certPaths['cert'];
        } elseif (file_exists($certGenPaths['cert'])) {
            $certFile = $certGenPaths['cert'];
        }

        if (file_exists($certPaths['key'])) {
            $keyFile = $certPaths['key'];
        } elseif (file_exists($certGenPaths['key'])) {
            $keyFile = $certGenPaths['key'];
        }

        if ($certFile) {
            $zip->addFile($certFile, 'certificado_' . basename($certFile));
            $archivosIncluidos++;
        }

        if ($keyFile) {
            $zip->addFile($keyFile, 'clave_' . basename($keyFile));
            $archivosIncluidos++;
        }

        // Resumen de configuración
        $resumen  = "══════════════════════════════════════════════════\n";
        $resumen .= "  MULTIPOS — RESGUARDO DE CERTIFICADOS\n";
        $resumen .= "══════════════════════════════════════════════════\n\n";
        $resumen .= "Empresa:        {$empresa->nombre_comercial}\n";
        $resumen .= "CUIT:           {$empresa->cuit}\n";
        $resumen .= "Punto de Venta: " . ($empresa->punto_venta ?? 'No definido') . "\n";
        $resumen .= "──────────────────────────────────────────────────\n\n";
        $resumen .= "CERTIFICADOS AFIP / ARCA:\n";
        $resumen .= "  Certificado (.crt): " . ($certFile ? 'INCLUIDO (' . basename($certFile) . ')' : 'NO ENCONTRADO') . "\n";
        $resumen .= "  Clave (.key):       " . ($keyFile ? 'INCLUIDO (' . basename($keyFile) . ')' : 'NO ENCONTRADO') . "\n";
        $resumen .= "  Modo AFIP:          " . ($empresa->afip_mod ? 'Producción' : 'Testing (Homologación)') . "\n\n";
        $resumen .= "ARCHIVOS INCLUIDOS: {$archivosIncluidos}\n";
        $resumen .= "──────────────────────────────────────────────────\n";
        $resumen .= "Fecha de generación: " . date('Y-m-d H:i:s') . "\n\n";
        $resumen .= "INSTRUCCIONES DE RESTAURACIÓN:\n";
        $resumen .= "  1. Subir los archivos .crt y .key a la carpeta /ARCA del servidor\n";
        $resumen .= "  2. Nombrarlos como: empresa_{$empresa->id}_cert.crt y empresa_{$empresa->id}_key.key\n";
        $resumen .= "  3. Ejecutar: php artisan optimize:clear\n";

        $zip->addFromString('_INSTRUCCIONES.txt', $resumen);
        $zip->close();

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
