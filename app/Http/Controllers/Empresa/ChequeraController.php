<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Chequera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChequeraController extends Controller
{
    /**
     * Listado de chequeras
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;
        $chequeras = Chequera::where('empresa_id', $empresaId)
            ->withCount(['cheques as emitidos_count'])
            ->orderBy('activo', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('empresa.tesoreria.chequeras.index', compact('chequeras'));
    }

    /**
     * Almacenar nueva chequera
     */
    public function store(Request $request)
    {
        $request->validate([
            'banco'          => 'required|string|max:255',
            'tipo'           => 'required|in:fisica,echeck',
            'numero_cuenta'  => 'required|string|max:255',
            'desde'          => 'required_if:tipo,fisica|integer|min:0',
            'hasta'          => 'required_if:tipo,fisica|integer|gt:desde',
            'proximo_numero' => 'nullable|integer',
        ]);

        $empresaId = Auth::user()->empresa_id;

        Chequera::create([
            'empresa_id'     => $empresaId,
            'banco'          => $request->banco,
            'tipo'           => $request->tipo,
            'sucursal'       => $request->sucursal,
            'numero_cuenta'  => $request->numero_cuenta,
            'tipo_cuenta'    => $request->tipo_cuenta ?? 'cuenta_corriente',
            'desde'          => $request->desde,
            'hasta'          => $request->hasta,
            'proximo_numero' => $request->proximo_numero ?? ($request->desde ?? 1),
            'notas'          => $request->notas,
        ]);

        return back()->with('success', 'Chequera creada con éxito.');
    }

    /**
     * Actualizar estado o notas de chequera
     */
    public function update(Request $request, Chequera $chequera)
    {
        $this->authorizeAccess($chequera);

        $request->validate([
            'proximo_numero' => 'sometimes|integer|between:' . $chequera->desde . ',' . ($chequera->hasta + 1),
        ]);

        $chequera->update($request->only(['proximo_numero', 'activo', 'notas']));

        return back()->with('success', 'Chequera actualizada.');
    }

    /**
     * Eliminar chequera (solo si no tiene cheques emitidos)
     */
    public function destroy(Chequera $chequera)
    {
        $this->authorizeAccess($chequera);

        if ($chequera->cheques()->exists()) {
            return back()->with('error', 'No se puede eliminar una chequera que ya tiene cheques emitidos.');
        }

        $chequera->delete();

        return back()->with('success', 'Chequera eliminada.');
    }

    protected function authorizeAccess(Chequera $chequera)
    {
        if ($chequera->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }
    }
}
