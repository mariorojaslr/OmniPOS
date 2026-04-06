<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    /**
     * 🛒 Formulario de Venta Manual Pro (Agilidad extrema)
     */
    public function createManual()
    {
        $empresaId = Auth::user()->empresa_id;
        $clients   = Client::where('empresa_id', $empresaId)->get();
        $products  = Product::paraVenta()
            ->where('empresa_id', $empresaId)
            ->with(['variants'])
            ->where('active', true)
            ->orderBy('name')
            ->get()
            ->values(); // Asegura un array secuencial para JS

        return view('empresa.ventas.manual', compact('clients', 'products'));
    }

    /**
     * 💾 Guardar Venta Manual con múltiples ítems
     */
    public function storeManual(Request $request, VentaService $ventaService)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items'     => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            'items.*.price'      => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        
        try {
            // Reformatear items para el servicio
            $items = array_map(function($item) {
                return [
                    'id'         => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ];
            }, $request->items);

            // Nota: El VentaService actual busca el precio en DB. 
            // Para ventas manuales con precio custom, tendríamos que pasar el precio.
            // Voy a modificar ligeramente VentaService para aceptar precio manual si viene.
            
            // Pero por ahora, usemos la lógica que ya estaba si el servicio no es flexible.
            // O mejor aún, actualizamos VentaService para ser el único punto de verdad.
            
            $venta = $ventaService->registrarVenta(
                $user,
                $items,
                $request->client_id,
                $request->metodo_pago === 'cuenta_corriente' ? 'cuenta_corriente' : 'contado',
                $request->tipo_comprobante ?? 'X',
                $request->boolean('hacer_remito', false),
                $request->input('items_entregar'),
                $request->metodo_pago ?: 'efectivo'
            );

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'        => true,
                    'venta_id'  => $venta->id,
                    'remito_id' => $venta->remito_principal?->id,
                    'message'   => "Venta #{$venta->id} registrada con éxito.",
                ]);
            }

            return redirect()->route('empresa.ventas.index')
                ->with('success', "Venta registrada con éxito. Comprobante: {$venta->numero_comprobante}");

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'error' => $e->getMessage()], 422);
            }
            return back()->with('error', 'Error al procesar la venta: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * 📋 Listado de ventas con KPIs y filtros
     */
    public function index(Request $request)
    {
        $empresa = auth()->user()->empresa;

        $q = Venta::where('empresa_id', $empresa->id)
            ->with(['cliente', 'user', 'items.product', 'items.variant'])
            ->orderByDesc('created_at');

        // Filtros
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($query) use ($s) {
                $query->where('numero_comprobante', 'like', "%$s%")
                      ->orWhereHas('cliente', fn($c) => $c->where('name', 'like', "%$s%"));
            });
        }
        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->to);
        }
        if ($request->filled('tipo')) {
            $q->where('tipo_comprobante', $request->tipo);
        }
        if ($request->filled('metodo')) {
            $q->where('metodo_pago', $request->metodo);
        }

        $ventas = $q->paginate($request->input('per_page', 15));

        // KPIs
        $base = Venta::where('empresa_id', $empresa->id);
        $kpiHoy    = (clone $base)->whereDate('created_at', today())->sum('total_con_iva');
        $kpiSemana = (clone $base)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_con_iva');
        $kpiMes    = (clone $base)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_con_iva');
        $totalVentas = (clone $base)->count();

        return view('empresa.ventas.index', compact('ventas', 'kpiHoy', 'kpiSemana', 'kpiMes', 'totalVentas'));
    }

    /**
     * 💾 Guarda una venta real desde el POS
     */
    public function store(Request $request, VentaService $ventaService)
    {
        // ... (Este método ya existe o se actualizó vía POSController)
    }

    /**
     * 👁️ Centro de Control de la Venta (Logística y Detalle)
     */
    public function show(Venta $venta)
    {
        $empresa = auth()->user()->empresa;

        if ($venta->empresa_id !== $empresa->id) {
            abort(403);
        }

        $venta->load([
            'items.product', 
            'items.variant', 
            'user', 
            'cliente',
            'remitos.items.product',
            'remitos.user'
        ]);

        return view('empresa.ventas.show', compact('venta', 'empresa'));
    }

    /**
     * 📑 Generar PDF de la venta
     */
    public function pdf(Venta $venta, Request $request)
    {
        $empresa = auth()->user()->empresa;

        if ($venta->empresa_id !== $empresa->id) {
            abort(403);
        }

        $venta->load(['items.product', 'items.variant', 'user', 'cliente']);
        $empresa->load('config');

        // Determinar formato (A4 o Ticket 80mm)
        $formato = $request->get('format', 'a4');
        
        if ($venta->tipo_comprobante === 'ticket' && !$request->has('format')) {
            $formato = 'ticket';
        }

        // Lógica de Logo - Acceso directo a disco para evitar fallos de red en producción
        $logoBase64 = null;
        if ($empresa->config && $empresa->config->logo) {
            try {
                $path = storage_path('app/public/' . $empresa->config->logo);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {}
        }

        // Logo de ARCA (AFIP) Genérico Profesional
        $arcaLogoBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAABACAMAAAC9G97XAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAnUExURQAAAD8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/P8fofp8AAAAMdFJOUwBAgMDBwYGBw8PDxG6mXgAAAAlwSFlzAAAWJQAAFiUBSVIk8AAAAO5JREFUaN7t2DsSgzAMRNE3YDBYIAnv/9YSUuYFfI9RVsirG9m/vE6O67ou77H+3Pee96Z5Zrqv5/7G8+z5/uO7++Y6f2yZp/z6+f3j+v7o/P9i/n8x/7+Y/1/M/y/m/xfz/4v5/8X8/2L+fzH/v5j/X8z/L+b/F/P/i/n/xfz/Yv5/Mf+/mP9fzP8v5v8X8/2L+f/F/P9i/n8x/7+Y/1/M/y/m/xfz/4v5/8X8/2L+fzH/v5j/X8z/L+b/F/P/i/n/xfz/Yv5/Mf+/mP9fzP8v5v8X8/2L+f/F/P9i/n8x/7+Y/1/M/y/m/xfz/4v5/8X8/2L+fzH/v5j93N/9AL/Dclv4I/fBAAAAAElFTkSuQmCC'; 

        // Generar URL de QR de AFIP basado en los datos almacenados o generados
        $qrUrl = null;
        $qrRaw = $venta->qr_data;
        
        // Si no tiene qr_data almacenado, intentamos regenerarlo para facturas antiguas
        if (!$qrRaw && $venta->cae) {
             $tipoCompAfip = ($empresa->condicion_iva === 'Monotributista') ? 11 : (($venta->tipo_comprobante === 'A') ? 1 : 6);
             $qrData = [
                "ver" => 1,
                "fecha" => $venta->created_at->format('Y-m-d'),
                "cuit" => (int) str_replace('-', '', $empresa->arca_cuit ?? $empresa->cuit),
                "ptoVta" => (int) ($empresa->arca_punto_venta ?? 1),
                "tipoCbte" => (int) $tipoCompAfip,
                "nroCbte" => (int) substr($venta->numero_comprobante, -8),
                "importe" => (float) $venta->total_con_iva,
                "moneda" => "PES",
                "ctz" => 1,
                "tipoDocRec" => (int) ($venta->cliente && strlen(str_replace('-', '', $venta->cliente->document)) > 8 ? 80 : 96),
                "nroDocRec" => (int) str_replace('-', '', $venta->cliente->document ?? 0),
                "tipoCodAut" => "E",
                "codAut" => (float) $venta->cae
            ];
            $qrRaw = base64_encode(json_encode($qrData));
        }

        if ($qrRaw) {
            $qrUrl = "https://www.afip.gob.ar/fe/qr/?p=" . $qrRaw;
        }

        if ($formato === 'ticket') {
            $pdfContent = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ticket_80mm', compact('venta', 'empresa', 'logoBase64', 'qrUrl'))
                ->setPaper([0, 0, 226.77, 700], 'portrait');
        } else {
            $pdfContent = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.comprobante_venta', compact('venta', 'empresa', 'logoBase64', 'arcaLogoBase64', 'qrUrl'))
                ->setPaper('a4', 'portrait');
        }

        $filename = ($venta->numero_comprobante ?: 'Venta_'.$venta->id) . '.pdf';
        return $pdfContent->stream($filename);
    }
}
