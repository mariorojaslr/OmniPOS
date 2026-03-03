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
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity < 1) {
            return back()->with('success', 'Cantidad inválida');
        }

        // Validar contra stock real
        if ($quantity > $product->stock_actual) {
            return back()->with('success', 'No hay suficiente stock disponible');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {

            $newQuantity = $cart[$product->id]['quantity'] + $quantity;

            if ($newQuantity > $product->stock_actual) {
                return back()->with('success', 'Stock insuficiente');
            }

            $cart[$product->id]['quantity'] = $newQuantity;

        } else {

            $cart[$product->id] = [
                "name"     => $product->name,
                "price"    => $product->price,
                "quantity" => $quantity,
                "image"    => optional($product->images->first())->path,
                "stock"    => $product->stock_actual
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
