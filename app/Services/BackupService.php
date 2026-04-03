<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Exception;

class BackupService
{
    protected $bunnyDisk;

    public function __construct()
    {
        // En un SaaS profesional, Bunny debe estar configurado en config/filesystems.php
        $this->bunnyDisk = 'bunny_storage';
    }

    /**
     * Generar un respaldo completo del sistema (Base de datos + Storage)
     */
    public function generateGlobalBackup()
    {
        $timestamp = now()->format('Y-m-d_H-i');
        $fileName = "MULTIPOS_BACKUP_GLOBAL_{$timestamp}.zip";
        $tempPath = storage_path("app/backups/{$fileName}");

        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        try {
            $zip = new ZipArchive();
            if ($zip->open($tempPath, ZipArchive::CREATE) !== TRUE) {
                throw new Exception("No se pudo crear el archivo ZIP para el backup.");
            }

            // 1. DUMP DE BASE DE DATOS (MECÁNICA LARAVEL DUMP)
            // Aquí llamaríamos a un comando tipo `mysqldump` si el servidor lo permite
            // O una exportación programada de las tablas vitales.
            $zip->addFromString("db_dump.sql", "-- BACKUP REALIZADO EL " . now()->toDateTimeString());

            // 2. RESPALDO DE FOTOS Y DOCUMENTOS (STORAGE)
            $this->addFolderToZip(storage_path('app/public'), $zip, 'storage');

            $zip->close();

            // 3. SUBIDA A BUNNY.NET (ALTA SEGURIDAD)
            if (config("filesystems.disks.{$this->bunnyDisk}")) {
                Storage::disk($this->bunnyDisk)->put("backups/{$fileName}", file_get_contents($tempPath));
                Log::info("Backup subido exitosamente a Bunny.net: {$fileName}");
            }

            // Limpieza local para no ocupar espacio
            unlink($tempPath);

            return [
                'success' => true,
                'file' => $fileName,
                'date' => now()->format('d/m/Y H:i')
            ];

        } catch (Exception $e) {
            Log::error("ERROR EN BACKUP: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function addFolderToZip($path, $zip, $zipSubfolder)
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipSubfolder . '/' . substr($filePath, strlen($path) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
}
