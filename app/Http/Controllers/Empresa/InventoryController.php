<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\InventorySession;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Central de mando: Gestión de sesiones de inventario (Administrador)
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;
        
        // Buscar sesión activa de la empresa
        $session = InventorySession::where('empresa_id', $empresaId)
            ->where('active', true)
            ->first();

        return view('empresa.inventory.hub', compact('session'));
    }

    /**
     * Iniciar una nueva sesión de inventario colaborativo
     */
    public function startSession()
    {
        $user = Auth::user();
        
        // Cerrar cualquier sesión previa activa
        InventorySession::where('empresa_id', $user->empresa_id)
            ->where('active', true)
            ->update(['active' => false]);

        // Crear nueva con UUID secreto
        $session = InventorySession::create([
            'empresa_id'    => $user->empresa_id,
            'uuid'          => (string) Str::uuid(),
            'created_by_id' => $user->id,
            'active'        => true
        ]);

        return back()->with('success', 'Sesión de inventario iniciada correctamente.');
    }

    /**
     * Finalizar la sesión actual (Revoca accesos)
     */
    public function stopSession()
    {
        InventorySession::where('empresa_id', Auth::user()->empresa_id)
            ->where('active', true)
            ->update(['active' => false]);

        return back()->with('success', 'Sesión de inventario finalizada. Los accesos han sido revocados.');
    }

    /**
     * Acceso de Invitado (Desde el escaneo del QR)
     */
    public function guestAccess($uuid)
    {
        $session = InventorySession::where('uuid', $uuid)
            ->where('active', true)
            ->first();

        if (!$session) {
            return "Esta sesión de inventario ya no existe o ha sido cerrada por el administrador.";
        }

        // Guardamos el pase en la sesión de MultiPOS 
        session(['inventory_guest_session' => $session->uuid]);
        session(['empresa_id' => $session->empresa_id]);

        // Redirigir a la vista de escaneo pero en modo "App" (Guest)
        return view('empresa.inventory.guest_scan', compact('session'));
    }

    /**
     * Ajustar stock vía AJAX (Soportando Invitados)
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'barcode'  => 'required|string',
            'mode'     => 'required|in:sum,set',
            'quantity' => 'required|numeric',
        ]);

        // Obtener empresa_id del usuario o de la sesión de invitado
        $empresaId = Auth::check() 
            ? Auth::user()->empresa_id 
            : session('empresa_id');

        if (!$empresaId) {
            return response()->json(['ok' => false, 'message' => 'Sesión expirada'], 401);
        }

        $barcode = $request->barcode;
        $mode    = $request->mode; 
        $qty     = $request->quantity;

        // 1. Buscar en variantes
        $variant = ProductVariant::where('barcode', $barcode)
            ->whereHas('product', function($q) use ($empresaId) {
                $q->where('empresa_id', $empresaId);
            })->first();

        if ($variant) {
            if ($mode === 'sum') {
                $variant->aumentarStock($qty, 'AJUSTE ESCÁNER');
            } else {
                $variant->ajustarStock($qty, 'AJUSTE ESCÁNER');
            }

            return response()->json([
                'ok'      => true,
                'name'    => $variant->product->name . " ({$variant->size}/{$variant->color})",
                'stock'   => $variant->stock,
                'message' => 'Stock actualizado correctamente'
            ]);
        }

        // 2. Buscar en productos básicos
        $product = Product::where('empresa_id', $empresaId)
            ->where('barcode', $barcode)
            ->first();

        if ($product) {
            if ($mode === 'sum') {
                $product->aumentarStock($qty, 'AJUSTE ESCÁNER');
            } else {
                $product->ajustarStock($qty, 'AJUSTE ESCÁNER');
            }

            return response()->json([
                'ok'      => true,
                'name'    => $product->name,
                'stock'   => $product->stock,
                'message' => 'Stock actualizado correctamente'
            ]);
        }

        return response()->json([
            'ok'      => false,
            'message' => 'Producto no encontrado con el código: ' . $barcode
        ], 404);
    }
}
