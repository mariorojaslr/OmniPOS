<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MigrateImagesToBunny extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bunny:sync-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all local product images securely to BunnyCDN';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $images = ProductImage::all();
        $this->info("Se encontraron " . $images->count() . " imágenes registradas en la base de datos.");

        $bunnyKey = env('BUNNY_PASSWORD'); // El Password principal es el API AccessKey
        $bunnyZone = env('BUNNY_USERNAME'); // Ej: gente-piola
        $bunnyHost = env('BUNNY_HOSTNAME'); // Ej: ny.storage.bunnycdn.com

        if (!$bunnyKey || !$bunnyZone || !$bunnyHost) {
            $this->error("Faltan las credenciales de BunnyCDN en el archivo .env");
            return;
        }

        $successCount = 0;
        $failCount = 0;
        $notFoundCount = 0;

        foreach ($images as $img) {
            $path = $img->path;

            // Construir la URL completa de la API de BunnyCDN
            $bunnyApiUrl = "https://{$bunnyHost}/{$bunnyZone}/{$path}";

            try {
                // Verificar si la imagen existe físicamente en el disco local
                if (Storage::disk('public')->exists($path)) {
                    $fileContents = Storage::disk('public')->get($path);

                    $this->info("Subiendo imagen: $path ...");

                    // Usar llamadas HTTP nativas garantizadas en servidores bloqueados
                    $response = Http::withHeaders([
                        'AccessKey' => $bunnyKey,
                        'Content-Type' => 'image/jpeg'
                    ])->withBody($fileContents, 'image/jpeg')->put($bunnyApiUrl);

                    if ($response->successful()) {
                        $this->line("<info>✓ Éxito:</info> $path");
                        $successCount++;
                    }
                    else {
                        $this->line("<error>✗ Falló BunnyCDN:</error> {$response->body()} -> $path");
                        $failCount++;
                    }
                }
                else {
                    $this->line("<comment>⚠ Archivo no encontrado en disco local:</comment> $path");
                    $notFoundCount++;
                }
            }
            catch (\Exception $e) {
                $this->line("<error>✗ Error del servidor:</error> " . $e->getMessage() . " -> $path");
                $failCount++;
            }
        }

        $this->info("\n--- RESUMEN DE MIGRACIÓN ---");
        $this->info("Subidas con éxito: $successCount");
        $this->info("Fallidas: $failCount");
        $this->info("No encontradas localmente: $notFoundCount");
        $this->info("----------------------------\n");
    }
}
