<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Empresa;
use App\Models\Client;

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
        $firstItem = reset($cart);
        $productId = $firstItem['product_id'] ?? array_key_first($cart);
        $product = Product::with('empresa')->find($productId);
        $empresa = $product ? $product->empresa : Empresa::first();

        return view('catalog.checkout', compact('cart','empresa'));
    }

    /*
    |--------------------------------------------------------------------------
    | Buscar cliente por email/teléfono (AJAX)
    |--------------------------------------------------------------------------
    */
    public function searchClient(Request $request)
    {
        $email = $request->get('email');
        $phone = $request->get('phone');
        $empresaId = $request->get('empresa_id');

        if (!$empresaId) return response()->json(['found' => false]);

        $client = Client::where('empresa_id', $empresaId)
            ->where(function($q) use ($email, $phone) {
                if ($email) $q->where('email', $email);
                if ($phone) $q->orWhere('phone', $phone);
            })->first();

        if ($client) {
            // Dividir nombre si es posible
            $parts = explode(' ', $client->name);
            $nombre = $parts[0] ?? '';
            $apellido = $parts[1] ?? '';

            return response()->json([
                'found' => true,
                'client' => [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'email' => $client->email,
                    'telefono' => $client->phone,
                    'direccion' => $client->address,
                    'ciudad' => $client->city,
                    'provincia' => $client->province,
                ]
            ]);
        }

        return response()->json(['found' => false]);
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

        // Buscar o crear cliente automáticamente
        $client = Client::where('empresa_id', $empresa->id)
            ->where(function($q) use ($request) {
                $q->where('email', $request->email)
                    ->orWhere('phone', $request->telefono);
            })->first();

        if (!$client) {
            $client = Client::create([
                'empresa_id' => $empresa->id,
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email,
                'phone' => $request->telefono,
                'address' => $request->direccion,
                'city' => $request->ciudad, // Campos nuevos opcionales
                'province' => $request->provincia,
                'type' => 'consumidor_final',
                'active' => true
            ]);
        } else {
            // Actualizar dirección si cambió
            if ($request->direccion && $client->address != $request->direccion) {
                $client->update(['address' => $request->direccion]);
            }
        }

        // Crear pedido
        $order = Order::create([
            'empresa_id' => $empresa->id,
            'client_id' => $client->id, // VINCULADO
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
        foreach($cart as $id => $item) {

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'precio'     => $item['price'],
                'cantidad'   => $item['quantity'],
                'subtotal'   => $item['price'] * $item['quantity'],
            ]);
        }

        // Vaciar carrito
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id);
    }

    public function success(Order $order)
    {
        $order->load(['items.product', 'empresa.config']);
        $empresa = $order->empresa;
        
        return view('catalog.success', compact('order', 'empresa'));
    }
}
