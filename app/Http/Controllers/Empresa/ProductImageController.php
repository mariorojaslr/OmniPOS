<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    public function create(Product $product)
    {
        $this->authorizeProduct($product);

        return view('empresa.products.images', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'images'   => 'required',
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

            Storage::disk('public')->put("$path/$filename", $image);

            ProductImage::create([
                'product_id' => $product->id,
                'path'       => "$path/$filename",
                'is_main'    => $product->images()->count() === 0,
                'order'      => $index,
            ]);
        }

        return back()->with('success', 'Imágenes subidas correctamente');
    }

    private function authorizeProduct(Product $product)
    {
        if ($product->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }
    }
}
