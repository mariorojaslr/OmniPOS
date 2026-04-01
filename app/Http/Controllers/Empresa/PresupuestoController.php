<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Venta; // Usaremos el mismo modelo de Ventas para presupuestos por ahora (con un flag o similar si existe) o simplemente el controlador base.

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

        // Indicadores reales
        $stats = [
            'total' => $empresa->presupuestos()->count(),
            'pendientes' => $empresa->presupuestos()->where('estado', 'pendiente')->count(),
            'aceptados' => $empresa->presupuestos()->where('estado', 'aceptado')->count(),
            'vencidos' => $empresa->presupuestos()->where('vencimiento', '<', now())->where('estado', 'pendiente')->count(),
        ];

        return view('empresa.presupuestos.index', compact('empresa', 'presupuestos', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        return view('empresa.presupuestos.create', [
            'empresa'  => $empresa,
            'clientes' => $empresa->clients()->orderBy('name')->get(),
            'productos'=> $empresa->products()->orderBy('nombre')->get(),
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

        // Generar número de presupuesto (PRE-000X)
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
                'vencimiento' => \Carbon\Carbon::parse($request->fecha)->addDays($request->validez),
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
            return redirect()->route('empresa.presupuestos.index')->with('success', "Presupuesto {$numero} generado con éxito.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al generar el presupuesto: ' . $e->getMessage());
        }
    }
}
