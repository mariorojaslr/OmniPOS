<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Empresa;

class CartController extends Controller
{
    /*
    |---------------------------------------------
    | VER CARRITO
    |---------------------------------------------
    */
    public function index()
    {
        $cart = session()->get('cart', []);

        /*
        |--------------------------------------------------
        | Resolver empresa para layout del catálogo
        |--------------------------------------------------
        */
        $empresa = null;

        if (!empty($cart)) {
            $firstProductId = array_key_first($cart);
            $product = Product::with('empresa')->find($firstProductId);

            if ($product) {
                $empresa = $product->empresa;
            }
        }

        // Fallback si carrito vacío
        if (!$empresa) {
            $empresa = Empresa::first();
        }

        return view('catalog.cart', compact('cart', 'empresa'));
    }

    /*
    |---------------------------------------------
    | AGREGAR PRODUCTO
    |---------------------------------------------
    */
    public function add(Request $request, Product $product)
    {
        $quantity  = (int) $request->input('quantity', 1);
        $variantId = $request->input('variant_id');

        if ($quantity < 1) {
            return back()->with('success', 'Cantidad inválida');
        }

        $variant = null;
        if ($variantId) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if (!$variant || $variant->product_id !== $product->id) {
                return back()->with('success', 'Variante inválida');
            }
        }

        // Validar contra stock real (variante o producto base)
        $stockDisponible = $variant ? $variant->stock : $product->stock;

        if ($quantity > $stockDisponible) {
            return back()->with('success', 'No hay suficiente stock disponible');
        }

        $cart = session()->get('cart', []);
        
        // El ID del carrito será compuesto si hay variante: "PROD_ID-VAR_ID"
        $cartKey = $variantId ? $product->id . '-' . $variantId : $product->id;

        if (isset($cart[$cartKey])) {

            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;

            if ($newQuantity > $stockDisponible) {
                return back()->with('success', 'Stock insuficiente');
            }

            $cart[$cartKey]['quantity'] = $newQuantity;

        } else {

            $cart[$cartKey] = [
                "product_id" => $product->id,
                "variant_id" => $variantId,
                "name"       => $product->name . ($variant ? " ({$variant->size} / {$variant->color})" : ""),
                "price"      => $variant ? ($variant->price ?: $product->price) : $product->price,
                "quantity"   => $quantity,
                "image"      => optional($product->images->first())->url,
                "stock"      => $stockDisponible
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Producto agregado al carrito');
    }

    /*
    |---------------------------------------------
    | ACTUALIZAR CANTIDAD
    |---------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $quantity = (int) $request->quantity;

            if ($quantity < 1) {
                return back()->with('success', 'Cantidad inválida');
            }

            // Validar stock guardado
            if (isset($cart[$id]['stock']) && $quantity > $cart[$id]['stock']) {
                return back()->with('success', 'No hay suficiente stock disponible');
            }

            $cart[$id]['quantity'] = $quantity;

            session()->put('cart', $cart);
        }

        return back()->with('success', 'Cantidad actualizada');
    }

    /*
    |---------------------------------------------
    | ELIMINAR PRODUCTO
    |---------------------------------------------
    */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Producto eliminado');
    }
}
