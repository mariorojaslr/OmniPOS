<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Empresa;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar checkout
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $cart = session()->get('cart', []);

        if(empty($cart)) {
            return redirect()->route('catalog.index');
        }

        // Resolver empresa desde primer producto
        $firstProductId = array_key_first($cart);
        $product = Product::with('empresa')->find($firstProductId);
        $empresa = $product ? $product->empresa : Empresa::first();

        return view('catalog.checkout', compact('cart','empresa'));
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar pedido
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if(empty($cart)) {
            return redirect()->route('checkout.index');
        }

        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'metodo_entrega' => 'required',
            'metodo_pago' => 'required'
        ]);

        // Resolver empresa
        $firstProductId = array_key_first($cart);
        $product = Product::with('empresa')->find($firstProductId);
        $empresa = $product ? $product->empresa : Empresa::first();

        // Calcular total
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Crear pedido
        $order = Order::create([
            'empresa_id' => $empresa->id,
            'nombre_cliente' => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'metodo_entrega' => $request->metodo_entrega,
            'metodo_pago' => $request->metodo_pago,
            'estado' => 'pendiente',
            'total' => $total,
        ]);

        // Crear items
        foreach($cart as $productId => $item) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'precio' => $item['price'],
                'cantidad' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        // Vaciar carrito
        session()->forget('cart');

        return redirect()->route('catalog.index', $empresa->id)
                         ->with('success','Pedido enviado correctamente. La empresa se contactará contigo.');
    }
}
