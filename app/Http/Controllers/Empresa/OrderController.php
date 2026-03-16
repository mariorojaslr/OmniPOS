<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Venta;
use App\Models\VentaItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $query = Order::where('empresa_id', $empresaId);

        if ($request->status) {
            $query->where('estado', $request->status);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nombre_cliente', 'like', "%{$request->search}%")
                  ->orWhere('id', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->orderByDesc('id')->paginate(15);

        return view('empresa.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['items.product', 'items.variant']);
        
        return view('empresa.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $oldStatus = $order->estado;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back();
        }

        DB::transaction(function() use ($order, $oldStatus, $newStatus) {
            
            $order->estado = $newStatus;
            $order->save();

            // LÓGICA DE INVENTARIO
            // Si pasa a "En Proceso" o "Armado" y antes era "Pendiente", descontamos stock
            $statusesQueDescontan = [Order::STATUS_PROCESO, Order::STATUS_ARMADO, Order::STATUS_ENVIADO, Order::STATUS_ENTREGADO];
            
            if (in_array($newStatus, $statusesQueDescontan) && !in_array($oldStatus, $statusesQueDescontan)) {
                $this->procesarDescuentoInventario($order);
            }

            // Si se cancela y antes estaba en un estado que descontó, devolvemos stock
            if ($newStatus === Order::STATUS_CANCELADO && in_array($oldStatus, $statusesQueDescontan)) {
                $this->revertirInventario($order);
            }

            // Si pasa a "Entregado", lo convertimos en Venta (si no se ha hecho antes)
            if ($newStatus === Order::STATUS_ENTREGADO && !$order->venta_id) {
                $this->convertirAVenta($order);
            }
        });

        return back()->with('success', 'Estado del pedido actualizado correctamente');
    }

    private function convertirAVenta(Order $order)
    {
        // Crear la Venta
        $venta = Venta::create([
            'empresa_id' => $order->empresa_id,
            'user_id' => auth()->id(),
            'cliente_nombre' => $order->nombre_cliente,
            'cliente_condicion' => 'consumidor_final',
            'subtotal' => $order->total / 1.21, // Asumiendo IVA 21% para reportes
            'iva' => $order->total - ($order->total / 1.21),
            'total' => $order->total,
            'metodo_pago' => $order->metodo_pago,
            'created_at' => now(),
        ]);

        // Crear los items de la venta
        foreach ($order->items as $item) {
            VentaItem::create([
                'venta_id' => $venta->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'cantidad' => $item->cantidad,
                'precio_unitario_sin_iva' => $item->precio / 1.21,
                'subtotal_item_sin_iva' => ($item->precio * $item->cantidad) / 1.21,
                'iva_item' => ($item->precio * $item->cantidad) - (($item->precio * $item->cantidad) / 1.21),
                'total_item_con_iva' => $item->precio * $item->cantidad,
            ]);
        }

        // Vincular el pedido con la venta
        $order->venta_id = $venta->id;
        $order->save();
    }

    private function procesarDescuentoInventario(Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $item->variant->descontarStock($item->cantidad, "PEDIDO CATALOGO #{$order->id}");
            } else {
                $item->product->descontarStock($item->cantidad, "PEDIDO CATALOGO #{$order->id}");
            }
        }
    }

    private function revertirInventario(Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $item->variant->aumentarStock($item->cantidad, "CANCELACION PEDIDO #{$order->id}");
            } else {
                $item->product->aumentarStock($item->cantidad, "CANCELACION PEDIDO #{$order->id}");
            }
        }
    }

    /**
     * PDF: Etiqueta de envío con QR
     */
    public function printLabel(Order $order)
    {
        $this->authorizeOrder($order);
        $empresa = auth()->user()->empresa;

        // Generar QR (usando un servicio online simple por ahora para evitar dependencias pesadas si no están instaladas)
        // URL de control interna o simplemente el ID del pedido
        $qrData = route('empresa.orders.show', $order->id);
        $qrUrl = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=" . urlencode($qrData) . "&choe=UTF-8";

        $pdf = Pdf::loadView('pdf.order_label', compact('order', 'empresa', 'qrUrl'))
                  ->setPaper([0, 0, 283.46, 283.46]); // 10x10cm aprox

        return $pdf->stream("etiqueta-pedido-{$order->id}.pdf");
    }

    /**
     * PDF: Hoja de ruta / Detalle para armado
     */
    public function printPicking(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['items.product', 'items.variant']);
        $empresa = auth()->user()->empresa;

        $pdf = Pdf::loadView('pdf.order_picking', compact('order', 'empresa'));

        return $pdf->stream("picking-list-{$order->id}.pdf");
    }

    private function authorizeOrder(Order $order)
    {
        if ($order->empresa_id !== auth()->user()->empresa_id) {
            abort(403);
        }
    }
}
