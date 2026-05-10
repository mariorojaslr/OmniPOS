<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class ServicioController extends Controller
{
    public function index()
    {
        $empresa = Auth::user()->empresa;
        $servicios = $empresa->servicios()->orderBy('categoria')->orderBy('nombre')->get();
        return view('empresa.servicios.index', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
            'comision_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        $empresa = Auth::user()->empresa;
        $empresa->servicios()->create($request->all());

        return back()->with('success', 'Servicio creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $servicio = Auth::user()->empresa->servicios()->findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
            'comision_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        $servicio->update($request->all());

        return back()->with('success', 'Servicio actualizado correctamente');
    }

    public function destroy($id)
    {
        $servicio = Auth::user()->empresa->servicios()->findOrFail($id);
        $servicio->delete();
        return back()->with('success', 'Servicio eliminado');
    }
}
