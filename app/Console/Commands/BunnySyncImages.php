<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BunnySyncImages extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'bunny:sync-images {--force : Sobrescribir archivos existentes en Bunny}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Sincroniza todas las imágenes locales de productos con Bunny Storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("------------------------------------------------------------");
        $this->info("📸 INICIANDO SINCRONIZACIÓN CON BUNNY STORAGE");
        $this->info("------------------------------------------------------------");

        $bunnyKey = env('BUNNY_PASSWORD');
        $bunnyZone = env('BUNNY_USERNAME');
        $bunnyHost = env('BUNNY_HOSTNAME');

        if (!$bunnyKey || !$bunnyZone || !$bunnyHost) {
            $this->error("❌ Error: Faltan credenciales de Bunny en el archivo .env");
            return 1;
        }

        $images = ProductImage::all();
        $this->info("Se encontraron " . $images->count() . " imágenes en la base de datos.");

        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $successCount = 0;
        $failCount = 0;
        $skipCount = 0;

        foreach ($images as $img) {
            $filePath = $img->path;

            // Verificar si existe localmente
            if (!Storage::disk('public')->exists($filePath)) {
                $this->warn("\n⚠️  Archivo local no encontrado: " . $filePath);
                $failCount++;
                $bar->advance();
                continue;
            }

            // Intentar subir a Bunny
            try {
                $fileContents = Storage::disk('public')->get($filePath);
                $bunnyApiUrl = "https://{$bunnyHost}/{$bunnyZone}/" . ltrim($filePath, '/');

                // Si no es force, podríamos intentar un HEAD para ver si ya existe, 
                // pero Bunny Storage API es un poco limitada para eso. Subimos directo.
                
                $response = Http::withHeaders([
                    'AccessKey' => $bunnyKey,
                    'Content-Type' => 'image/jpeg'
                ])->withBody($fileContents, 'image/jpeg')->put($bunnyApiUrl);

                if ($response->successful()) {
                    $successCount++;
                } else {
                    Log::error("Fallo al sincronizar con Bunny: " . $response->body());
                    $failCount++;
                }
            } catch (\Exception $e) {
                Log::error("Excepción en BunnySync: " . $e->getMessage());
                $failCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\n\n------------------------------------------------------------");
        $this->info("✅ SINCRONIZACIÓN FINALIZADA");
        $this->info("➤ Éxitos: $successCount");
        $this->info("➤ Fallos: $failCount");
        $this->info("------------------------------------------------------------");

        return 0;
    }
}
