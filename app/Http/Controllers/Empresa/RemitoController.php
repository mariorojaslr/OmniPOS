<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Remito;
use App\Models\RemitoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RemitoController extends Controller
{
    /**
     * 📋 Listado centralizado de Remitos
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $q = Remito::where('empresa_id', $empresaId)
            ->with(['cliente', 'user', 'venta'])
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function($query) use ($s) {
                $query->where('numero_remito', 'like', "%$s%")
                      ->orWhereHas('cliente', fn($c) => $c->where('name', 'like', "%$s%"));
            });
        }

        $remitos = $q->paginate(15);

        return view('empresa.remitos.index', compact('remitos'));
    }

    /**
     * 🚚 Formulario para generar una entrega parcial (Remito)
     */
    public function create(Venta $venta)
    {
        $empresaId = Auth::user()->empresa_id;

        if ($venta->empresa_id !== $empresaId) {
            abort(403);
        }

        $venta->load(['items.product', 'items.variant', 'cliente']);

        // Filtrar solo items que tengan saldo pendiente de entrega
        $itemsPendientes = $venta->items->filter(fn($i) => $i->cantidad_pendiente > 0);

        if ($itemsPendientes->isEmpty()) {
            return redirect()->route('empresa.ventas.show', $venta->id)
                ->with('error', 'Esta venta ya no tiene mercadería pendiente en guarda.');
        }

        return view('empresa.remitos.create', compact('venta', 'itemsPendientes'));
    }

    /**
     * 💾 Guardar el Remito y actualizar saldos físicos
     */
    public function store(Request $request, Venta $venta)
    {
        $empresaId = Auth::user()->empresa_id;

        if ($venta->empresa_id !== $empresaId) {
            abort(403);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.cantidad' => 'required|numeric|min:0',
        ]);

        // 📝 Lógica de Formateo de Número de Remito (e.g. 1-1 -> 0001-00000001)
        $numeroRemito = $request->numero_remito;
        if ($numeroRemito && str_contains($numeroRemito, '-')) {
            $parts = explode('-', $numeroRemito);
            if (count($parts) === 2) {
                $suc = str_pad(trim($parts[0]), 4, '0', STR_PAD_LEFT);
                $num = str_pad(trim($parts[1]), 8, '0', STR_PAD_LEFT);
                $numeroRemito = $suc . '-' . $num;
            }
        }

        try {
            DB::beginTransaction();

            // 1. Crear Cabecera del Remito
            $remito = Remito::create([
                'empresa_id'     => $empresaId,
                'venta_id'       => $venta->id,
                'user_id'        => Auth::id(),
                'client_id'      => $venta->client_id,
                'numero_remito'  => $numeroRemito,
                'fecha_entrega'  => now(),
                'observaciones'  => $request->observaciones,
            ]);


            $totalItemsEntregados = 0;

            foreach ($request->items as $ventaItemId => $data) {
                $cantidadAEntregar = (float) $data['cantidad'];

                if ($cantidadAEntregar <= 0) continue;

                $itemVenta = VentaItem::findOrFail($ventaItemId);

                // Validar que no se entregue más de lo pendiente
                if ($cantidadAEntregar > $itemVenta->cantidad_pendiente) {
                    throw new \Exception("La cantidad a entregar de {$itemVenta->product->name} excede el saldo en guarda.");
                }

                // 2. Crear ítem del Remito
                RemitoItem::create([
                    'remito_id'     => $remito->id,
                    'venta_item_id' => $itemVenta->id,
                    'product_id'    => $itemVenta->product_id,
                    'variant_id'    => $itemVenta->variant_id,
                    'cantidad'      => $cantidadAEntregar,
                ]);

                // 3. ✨ ACTUALIZAR SALDO FÍSICO en la Venta
                $itemVenta->increment('cantidad_entregada', $cantidadAEntregar);
                $totalItemsEntregados++;
            }

            if ($totalItemsEntregados === 0) {
                throw new \Exception("Debes indicar al menos una cantidad válida para entregar.");
            }

            DB::commit();

            return redirect()->route('empresa.ventas.show', $venta->id)
                ->with('success', "Remito generado con éxito. Se actualizaron los saldos físicos.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error logístico: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * 🖨️ Generar PDF del Remito (Documento de Entrega)
     */
    public function pdf(Remito $remito)
    {
        $empresa = auth()->user()->empresa;

        if ($remito->empresa_id !== $empresa->id) {
            abort(403);
        }

        $remito->load(['items.product', 'items.variant', 'user', 'cliente', 'venta']);
        $empresa->load('config');

        // Lógica de Logo en Base64 para evitar errores de renderizado
        $logoBase64 = null;
        if ($empresa->config && $empresa->config->logo) {
            $logoUrl = $empresa->config->logo_url;
            try {
                if (str_starts_with($logoUrl, 'http')) {
                    $data = file_get_contents($logoUrl);
                } else {
                    $localPath = public_path(ltrim($logoUrl, '/'));
                    if (!file_exists($localPath)) {
                        $localPath = storage_path('app/public/' . $empresa->config->logo);
                    }
                    if (file_exists($localPath)) {
                        $data = file_get_contents($localPath);
                    }
                }
                if (isset($data)) {
                    $logoBase64 = 'data:image/png;base64,' . base64_encode($data);
                }
            } catch (\Exception $e) { /* Fallback silente */ }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.remito', compact('remito', 'empresa', 'logoBase64'))
                ->setPaper('a4', 'portrait');

        $filename = ($remito->numero_remito ?: 'Remito_'.$remito->id) . '.pdf';
        return $pdf->stream($filename);
    }
}


