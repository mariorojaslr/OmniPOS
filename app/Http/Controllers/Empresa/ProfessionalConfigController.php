<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfesionalConfig;
use App\Models\AcuerdoProfesional;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProfessionalConfigController extends Controller
{
    public function edit(User $user)
    {
        if ($user->empresa_id != Auth::user()->empresa_id) abort(403);

        $config = $user->profesionalConfig ?? new ProfesionalConfig(['user_id' => $user->id]);
        
        if (!$config->token_portal) {
            $config->token_portal = Str::random(40);
        }

        $servicios = Auth::user()->empresa->servicios()->get();
        $acuerdos = AcuerdoProfesional::where('user_id', $user->id)->get()->keyBy('servicio_id');

        return view('empresa.usuarios.config_profesional', compact('user', 'config', 'servicios', 'acuerdos'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->empresa_id != Auth::user()->empresa_id) abort(403);

        $request->validate([
            'tipo_contrato' => 'required|in:fijo,comision,mixto',
            'sueldo_base' => 'nullable|numeric|min:0',
            'tipo_comision' => 'required|in:porcentaje,fijo',
            'valor_comision' => 'nullable|numeric|min:0',
            'especialidades' => 'nullable|array',
            'acuerdos' => 'nullable|array'
        ]);

        $sueldo = $request->sueldo_base ?? 0;
        $comision = $request->valor_comision ?? 0;

        // Actualizar Configuración General
        $config = $user->profesionalConfig()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'tipo_contrato' => $request->tipo_contrato,
                'sueldo_base' => $sueldo,
                'tipo_comision' => $request->tipo_comision,
                'valor_comision' => $comision,
                'especialidades' => $request->especialidades,
                'token_portal' => ($user->profesionalConfig ? $user->profesionalConfig->token_portal : null) ?? Str::random(40)
            ]
        );

        // Actualizar Acuerdos Particulares
        if ($request->has('acuerdos')) {
            foreach ($request->acuerdos as $servicio_id => $data) {
                if (isset($data['valor']) && $data['valor'] !== null && $data['valor'] !== '') {
                    AcuerdoProfesional::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'servicio_id' => $servicio_id
                        ],
                        [
                            'empresa_id' => Auth::user()->empresa_id,
                            'tipo_comision' => $data['tipo'],
                            'valor' => $data['valor']
                        ]
                    );
                } else {
                    // Si el valor está vacío, eliminamos el acuerdo particular para que use el general
                    AcuerdoProfesional::where('user_id', $user->id)
                        ->where('servicio_id', $servicio_id)
                        ->delete();
                }
            }
        }

        return back()->with('success', 'Configuración profesional y acuerdos actualizados');
    }
}
