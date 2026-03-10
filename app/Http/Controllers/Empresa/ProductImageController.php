<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Pantalla de imágenes del producto
     |--------------------------------------------------------------------------
     */
    public function create(Product $product)
    {
        $this->authorizeProduct($product);

        return view('empresa.products.images', compact('product'));
    }

    /*
     |--------------------------------------------------------------------------
     | Subir imágenes
     |--------------------------------------------------------------------------
     */
    public function store(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'images' => 'required',
            'images.*' => 'image|max:5120',
        ]);

        if ($product->images()->count() >= 5) {
            return back()->withErrors('Máximo 5 imágenes permitidas');
        }

        $manager = new ImageManager(new Driver());

        foreach ($request->file('images') as $index => $file) {

            if ($product->images()->count() >= 5) {
                break;
            }

            $path = "products/{$product->empresa_id}/{$product->id}";
            $filename = uniqid() . '.jpg';

            $image = $manager
                ->read($file)
                ->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
                ->toJpeg(80);

            // "Modo Paranoico": Guardar físico local (Respaldo)
            Storage::disk('public')->put("$path/$filename", $image->toString());

            // Guardar en BunnyCDN (Almacenamiento primario en la nube)
            $bunnySuccess = true;
            try {
                Storage::disk('bunny_storage')->put("$path/$filename", $image->toString());
            }
            catch (\Exception $e) {
                // Si Bunny falla, reportamos pero la imagen ya está a salvo en local
                Log::error('Fallo al subir a BunnyCDN: ' . $e->getMessage());
                $bunnySuccess = false;
            }

            ProductImage::create([
                'product_id' => $product->id,
                'path' => "$path/$filename",
                'is_main' => $product->images()->count() === 0,
                'order' => $index,
            ]);
        }

        if (!$bunnySuccess) {
            return back()->with('success', 'Imagen guardada localmente, pero falló la conexión FTP con BunnyCDN. Revisa el .env y limpia caché.');
        }

        return back()->with('success', 'Imágenes subidas correctamente a BunnyCDN');
    }

    /*
     |--------------------------------------------------------------------------
     | ELIMINAR IMAGEN
     |--------------------------------------------------------------------------
     */
    public function destroy(Product $product, ProductImage $image)
    {
        $this->authorizeProduct($product);

        // Verifica que la imagen pertenece al producto
        if ($image->product_id !== $product->id) {
            abort(403);
        }

        // Borra archivo físico (Respaldo)
        Storage::disk('public')->delete($image->path);

        // Intenta borrar de BunnyCDN (Nube)
        try {
            Storage::disk('bunny_storage')->delete($image->path);
        }
        catch (\Exception $e) {
            Log::error('Fallo al eliminar de BunnyCDN: ' . $e->getMessage());
        }

        $wasMain = $image->is_main;

        // Borra registro DB
        $image->delete();

        /*
         |----------------------------------------------------------
         | Si borraste la imagen principal → asigna otra
         |----------------------------------------------------------
         */
        if ($wasMain) {
            $newMain = $product->images()->orderBy('order')->first();
            if ($newMain) {
                $newMain->update(['is_main' => true]);
            }
        }

        /*
         |----------------------------------------------------------
         | Reordenar imágenes
         |----------------------------------------------------------
         */
        $product->images()
            ->orderBy('id')
            ->get()
            ->each(function ($img, $index) {
            $img->update(['order' => $index]);
        });

        return back()->with('success', 'Imagen eliminada correctamente');
    }

    /*
     |--------------------------------------------------------------------------
     | Seguridad empresa
     |--------------------------------------------------------------------------
     */
    private function authorizeProduct(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }
    }
}
