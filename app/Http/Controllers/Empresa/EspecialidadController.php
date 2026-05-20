<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Especialidad;
use App\Models\Servicio;
use App\Models\User;
use App\Models\ProfesionalConfig;
use Illuminate\Support\Facades\Auth;

class EspecialidadController extends Controller
{
    public function index()
    {
        $empresa = Auth::user()->empresa;
        $especialidades = Especialidad::where('empresa_id', $empresa->id)
            ->orderBy('nombre')
            ->get();

        // Para cada especialidad, calculamos de manera dinámica la cantidad de prestaciones y profesionales asignados
        $especialidadesData = $especialidades->map(function ($esp) use ($empresa) {
            // Prestaciones con esta categoría
            $serviciosQuery = Servicio::where('empresa_id', $empresa->id)
                ->where('categoria', $esp->nombre);
            
            $serviciosIds = $serviciosQuery->pluck('id')->toArray();
            $cantServicios = count($serviciosIds);

            // Obtener lista completa de prácticas/servicios
            $servicios = $serviciosQuery->get()->map(function($serv) {
                return [
                    'nombre' => $serv->nombre,
                    'precio' => (float) $serv->precio,
                    'duracion_minutos' => (int) $serv->duracion_minutos,
                    'activo' => (bool) $serv->activo
                ];
            })->toArray();

            // Médicos asignados: aquellos que tienen en su profesional_config alguno de los IDs de servicios anteriores
            $cantProfesionales = 0;
            $medicos = [];
            if ($cantServicios > 0) {
                $configs = ProfesionalConfig::where('empresa_id', $empresa->id)->with('user')->get();
                foreach ($configs as $config) {
                    $espAsignadas = (array) $config->especialidades;
                    // Si hay alguna coincidencia de IDs
                    if (array_intersect($serviciosIds, $espAsignadas)) {
                        $cantProfesionales++;
                        if ($config->user) {
                            $medicos[] = [
                                'name' => $config->user->name,
                                'email' => $config->user->email,
                                'activo' => $config->user->activo
                            ];
                        }
                    }
                }
            }

            return (object) [
                'id' => $esp->id,
                'nombre' => $esp->nombre,
                'descripcion' => $esp->descripcion,
                'activo' => $esp->activo,
                'cant_servicios' => $cantServicios,
                'cant_profesionales' => $cantProfesionales,
                'medicos' => $medicos,
                'servicios' => $servicios
            ];
        });

        return view('empresa.especialidades.index', compact('especialidadesData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $empresa = Auth::user()->empresa;

        // Validar duplicado por empresa
        $existe = Especialidad::where('empresa_id', $empresa->id)
            ->where('nombre', $request->nombre)
            ->exists();

        if ($existe) {
            return back()->withErrors(['nombre' => 'La especialidad ya se encuentra registrada.']);
        }

        Especialidad::create([
            'empresa_id' => $empresa->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => true
        ]);

        return back()->with('success', 'Especialidad creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $empresa = Auth::user()->empresa;
        $especialidad = Especialidad::where('empresa_id', $empresa->id)->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'activo' => 'required|boolean'
        ]);

        // Validar duplicado excluyendo actual
        $existe = Especialidad::where('empresa_id', $empresa->id)
            ->where('nombre', $request->nombre)
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['nombre' => 'Ya existe otra especialidad con ese nombre.']);
        }

        // Si cambia el nombre, podemos actualizar las categorías de servicios asociados de forma automática
        $antiguoNombre = $especialidad->nombre;
        if ($antiguoNombre !== $request->nombre) {
            Servicio::where('empresa_id', $empresa->id)
                ->where('categoria', $antiguoNombre)
                ->update(['categoria' => $request->nombre]);
        }

        $especialidad->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo
        ]);

        return back()->with('success', 'Especialidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        $empresa = Auth::user()->empresa;
        $especialidad = Especialidad::where('empresa_id', $empresa->id)->findOrFail($id);

        // Desasociar o validar
        $serviciosCount = Servicio::where('empresa_id', $empresa->id)
            ->where('categoria', $especialidad->nombre)
            ->count();

        if ($serviciosCount > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar la especialidad porque contiene prestaciones/servicios asociados. Elimine o reasigne primero los servicios.']);
        }

        $especialidad->delete();

        return back()->with('success', 'Especialidad eliminada correctamente.');
    }
}
