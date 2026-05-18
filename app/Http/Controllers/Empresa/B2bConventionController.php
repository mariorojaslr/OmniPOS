<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\B2bConvention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Clase B2bConventionController
 * Administra el ABM de Convenios B2B para que la clínica de de alta agencias/municipalidades de control de certificados.
 */
class B2bConventionController extends Controller
{
    /**
     * Listar todos los convenios vigentes del inquilino.
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $conventions = B2bConvention::where('empresa_id', $empresaId)
            ->orderBy('nombre', 'asc')
            ->get();

        // 🩺 Auto-sanación: Generar tokens de acceso para convenios existentes sin token
        foreach ($conventions as $conv) {
            if (empty($conv->token_acceso)) {
                $conv->token_acceso = 'CONV-' . strtoupper(\Illuminate\Support\Str::random(24));
                $conv->save();
            }
        }

        $servicios = Auth::user()->empresa->servicios()->orderBy('nombre')->get();

        return view('empresa.hospitalization.convenios.index', compact('conventions', 'servicios'));
    }

    /**
     * Guardar un nuevo convenio.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'cuit' => 'nullable|string|max:20',
            'servicios_requeridos' => 'nullable|array',
        ]);

        $empresaId = Auth::user()->empresa_id;

        B2bConvention::create([
            'empresa_id' => $empresaId,
            'nombre' => $request->nombre,
            'cuit' => $request->cuit,
            'servicios_requeridos' => $request->servicios_requeridos,
            'activo' => true,
        ]);

        return redirect()->back()->with('success', 'Convenio registrado con éxito. Copie el enlace generado para compartir con la entidad.');
    }

    /**
     * Alternar el estado activo/inactivo del convenio.
     */
    public function toggleStatus($id)
    {
        $empresaId = Auth::user()->empresa_id;

        $convention = B2bConvention::where('id', $id)
            ->where('empresa_id', $empresaId)
            ->firstOrFail();

        $convention->activo = !$convention->activo;
        $convention->save();

        return redirect()->back()->with('success', 'El estado del convenio se ha actualizado correctamente.');
    }
}
