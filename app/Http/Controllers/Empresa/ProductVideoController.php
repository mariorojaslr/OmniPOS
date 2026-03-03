<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductVideoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR VIDEOS DEL PRODUCTO
    |--------------------------------------------------------------------------
    */
    public function index(Product $product)
    {
        $this->authorizeProduct($product);

        $videos = $product->videos()->latest()->get();

        return view('empresa.products.videos', compact('product', 'videos'));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR VIDEO YOUTUBE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'youtube_url' => 'required|url'
        ]);

        if ($product->videos()->count() >= 3) {
            return back()->withErrors('Máximo 3 videos permitidos por producto');
        }

        ProductVideo::create([
            'product_id'  => $product->id,
            'youtube_url' => $request->youtube_url
        ]);

        return back()->with('success', 'Video agregado correctamente');
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR VIDEO
    |--------------------------------------------------------------------------
    */
    public function destroy(Product $product, ProductVideo $video)
    {
        $this->authorizeProduct($product);

        if ($video->product_id !== $product->id) {
            abort(403);
        }

        $video->delete();

        return back()->with('success', 'Video eliminado correctamente');
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
