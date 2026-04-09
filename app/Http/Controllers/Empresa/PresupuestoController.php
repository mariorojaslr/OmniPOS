<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presupuesto;

class PresupuestoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        $presupuestos = $empresa->presupuestos()
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total'      => $empresa->presupuestos()->count(),
            'pendientes' => $empresa->presupuestos()->where('estado', 'pendiente')->count(),
            'aceptados'  => $empresa->presupuestos()->where('estado', 'aceptado')->count(),
            'vencidos'   => $empresa->presupuestos()->where('vencimiento', '<', now())->where('estado', 'pendiente')->count(),
        ];

        return view('empresa.presupuestos.index', compact('empresa', 'presupuestos', 'stats'));
    }

    public function create()
    {
        if (auth()->user()->role === 'usuario') {
            abort(403, 'No tienes permisos para crear presupuestos. Consulta con tu administrador.');
        }

        $user = Auth::user();
        $empresa = $user->empresa;

        return view('empresa.presupuestos.create', [
            'empresa'   => $empresa,
            'clientes'  => $empresa->clients()->orderBy('name')->get(),
            'productos' => $empresa->products()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'   => 'nullable|exists:clients,id',
            'fecha'       => 'required|date',
            'validez'     => 'required|integer|min:1',
            'items'       => 'required|array|min:1',
            'total_final' => 'required|numeric'
        ]);

        $user = Auth::user();
        $empresa = $user->empresa;

        $lastPresu = $empresa->presupuestos()->orderBy('id', 'desc')->first();
        $ultimoNro = $lastPresu ? intval(str_replace('PRE-', '', $lastPresu->numero)) : 0;
        $numero = 'PRE-' . str_pad($ultimoNro + 1, 4, '0', STR_PAD_LEFT);

        try {
            \DB::beginTransaction();

            $presupuesto = $empresa->presupuestos()->create([
                'user_id'     => $user->id,
                'client_id'   => $request->client_id,
                'numero'      => $numero,
                'fecha'       => $request->fecha,
                'vencimiento' => \Carbon\Carbon::parse($request->fecha)->addDays((int)$request->validez),
                'subtotal'    => $request->total_final,
                'total'       => $request->total_final,
                'notas'       => $request->notas,
                'estado'      => 'pendiente'
            ]);

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $presupuesto->items()->create([
                        'product_id'      => $item['product_id'],
                        'descripcion'     => $item['descripcion'],
                        'cantidad'        => $item['qty'],
                        'precio_unitario' => $item['price'],
                        'subtotal'        => $item['qty'] * $item['price'],
                        'total'           => $item['qty'] * $item['price'],
                    ]);
                }
            }

            \DB::commit();

            // REGISTRAR ACTIVIDAD
            \App\Models\ActivityLog::log("Generó el presupuesto {$numero} por $" . number_format($presupuesto->total, 2, ',', '.'), $presupuesto);

            return redirect()->route('empresa.presupuestos.index')->with('success', "Presupuesto {$numero} generado con éxito.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al generar el presupuesto: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (auth()->user()->role === 'usuario') {
            abort(403, 'No tienes permisos para editar presupuestos.');
        }

        $user = Auth::user();
        $empresa = $user->empresa;

        $presupuesto = $empresa->presupuestos()->with('items')->findOrFail($id);

        $itemsData = $presupuesto->items->map(function($i){
            return [
                'product_id'  => $i->product_id,
                'qty'         => (float)$i->cantidad,
                'price'       => (float)$i->precio_unitario,
                'descripcion' => $i->descripcion
            ];
        });

        return view('empresa.presupuestos.edit', [
            'empresa'     => $empresa,
            'presupuesto' => $presupuesto,
            'itemsData'   => $itemsData,
            'clientes'    => $empresa->clients()->orderBy('name')->get(),
            'productos'   => $empresa->products()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id'   => 'nullable|exists:clients,id',
            'fecha'       => 'required|date',
            'validez'     => 'required|integer|min:1',
            'items'       => 'required|array|min:1',
            'total_final' => 'required|numeric'
        ]);

        $user = Auth::user();
        $empresa = $user->empresa;
        $presupuesto = $empresa->presupuestos()->findOrFail($id);

        try {
            \DB::beginTransaction();

            $presupuesto->update([
                'client_id'   => $request->client_id,
                'fecha'       => $request->fecha,
                'vencimiento' => \Carbon\Carbon::parse($request->fecha)->addDays((int)$request->validez),
                'subtotal'    => $request->total_final,
                'total'       => $request->total_final,
                'notas'       => $request->notas,
                'estado'      => $request->status ?? $presupuesto->estado
            ]);

            $presupuesto->items()->delete();

            foreach ($request->items as $item) {
                if (!empty($item['product_id'])) {
                    $presupuesto->items()->create([
                        'product_id'      => $item['product_id'],
                        'descripcion'     => $item['descripcion'],
                        'cantidad'        => $item['qty'],
                        'precio_unitario' => $item['price'],
                        'subtotal'        => $item['qty'] * $item['price'],
                        'total'           => $item['qty'] * $item['price'],
                    ]);
                }
            }

            \DB::commit();

            // REGISTRAR ACTIVIDAD
            \App\Models\ActivityLog::log("Actualizó los datos del presupuesto {$presupuesto->numero}", $presupuesto);

            return redirect()->route('empresa.presupuestos.index')->with('success', "Presupuesto {$presupuesto->numero} actualizado con éxito.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al actualizar el presupuesto: ' . $e->getMessage());
        }
    }

    /**
     * Clonar un presupuesto existente: genera uno nuevo con los mismos ítems para editar.
     */
    public function clone($id)
    {
        if (auth()->user()->role === 'usuario') {
            abort(403, 'No tienes permisos para clonar documentos.');
        }

        $user = Auth::user();
        $empresa = $user->empresa;

        $original = $empresa->presupuestos()->with('items')->findOrFail($id);

        // Generar nuevo número
        $lastPresu = $empresa->presupuestos()->orderBy('id', 'desc')->first();
        $ultimoNro = $lastPresu ? intval(str_replace('PRE-', '', $lastPresu->numero)) : 0;
        $numero = 'PRE-' . str_pad($ultimoNro + 1, 4, '0', STR_PAD_LEFT);

        try {
            \DB::beginTransaction();

            $clon = $empresa->presupuestos()->create([
                'user_id'     => $user->id,
                'client_id'   => $original->client_id,
                'numero'      => $numero,
                'fecha'       => now()->toDateString(),
                'vencimiento' => now()->addDays(15)->toDateString(),
                'subtotal'    => $original->subtotal,
                'total'       => $original->total,
                'notas'       => $original->notas,
                'estado'      => 'pendiente',
            ]);

            foreach ($original->items as $item) {
                $clon->items()->create([
                    'product_id'      => $item->product_id,
                    'descripcion'     => $item->descripcion,
                    'cantidad'        => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'subtotal'        => $item->subtotal,
                    'total'           => $item->total,
                ]);
            }

            \DB::commit();

            // REGISTRAR ACTIVIDAD
            \App\Models\ActivityLog::log("Clonó el presupuesto {$original->numero} como {$numero}", $clon);

            return redirect()
                ->route('empresa.presupuestos.edit', $clon->id)
                ->with('info', "Se clonó el presupuesto {$original->numero} como {$numero}. Revise y ajuste los datos antes de confirmar.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al clonar el presupuesto: ' . $e->getMessage());
        }
    }

    /**
     * Convertir presupuesto en Factura: redirige al facturador manual con los datos pre-cargados.
     */
    public function convertirAFactura($id)
    {
        if (auth()->user()->role === 'usuario') {
            abort(403, 'No tienes permisos para facturar presupuestos manualmente.');
        }

        $user = Auth::user();
        $empresa = $user->empresa;

        $presupuesto = $empresa->presupuestos()->with('items.product', 'client')->findOrFail($id);

        // Guardamos en sesión para que el facturador manual los consuma
        session([
            'prefill_factura' => [
                'presupuesto_id'  => $presupuesto->id,
                'presupuesto_ref' => $presupuesto->numero,
                'client_id'       => $presupuesto->client_id,
                'items'           => $presupuesto->items->map(function($i) {
                    return [
                        'product_id'  => $i->product_id,
                        'descripcion' => $i->descripcion,
                        'qty'         => (float)$i->cantidad,
                        'price'       => (float)$i->precio_unitario,
                    ];
                })->values()->toArray(),
            ]
        ]);

        // Marcar el presupuesto como aceptado
        $presupuesto->update(['estado' => 'aceptado']);

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Inició la conversión del presupuesto {$presupuesto->numero} a Factura.", $presupuesto);

        return redirect()
            ->route('empresa.ventas.manual')
            ->with('info', "Presupuesto {$presupuesto->numero} convertido. Los ítems han sido pre-cargados. Seleccione el tipo de factura y confirme.");
    }

    public function pdf($id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        $presupuesto = $empresa->presupuestos()
            ->with(['client', 'items.product.images'])
            ->findOrFail($id);

        $logoBase64 = null;
        if ($empresa->config && $empresa->config->logo) {
            $logoPath = $empresa->config->logo;
            $fullLogoPath = storage_path('app/public/' . $logoPath);
            if (file_exists($fullLogoPath)) {
                $type = pathinfo($fullLogoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullLogoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        foreach ($presupuesto->items as $item) {
            $item->image_base64 = null;
            if ($item->product && $item->product->images->count() > 0) {
                $imgPath = $item->product->images->first()->path;
                $fullImgPath = storage_path('app/public/' . $imgPath);
                if (file_exists($fullImgPath)) {
                    $type = pathinfo($fullImgPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($fullImgPath);
                    $item->image_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.presupuesto', [
            'empresa'     => $empresa,
            'presupuesto' => $presupuesto,
            'logoBase64'  => $logoBase64
        ]);

        return $pdf->stream("Presupuesto-{$presupuesto->numero}.pdf");
    }
}
