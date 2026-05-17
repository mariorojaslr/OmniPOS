<?php
namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\AffiliatePlan;
use Illuminate\Http\Request;

class AffiliatePlanController extends Controller
{
    public function index()
    {
        $empresa = auth()->user()->empresa;
        $plans = AffiliatePlan::where('empresa_id', $empresa->id)->get();
        return view('empresa.affiliate_plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
        ]);

        AffiliatePlan::create([
            'empresa_id' => auth()->user()->empresa_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'features' => $request->features, // Array de prestaciones
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Plan creado correctamente con sus prestaciones.');
    }

    public function update(Request $request, AffiliatePlan $affiliatePlan)
    {
        // Validación manual de seguridad para asegurar que el plan pertenece a la empresa del usuario
        if ($affiliatePlan->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['features'] = $request->features ?? [];

        $affiliatePlan->update($data);

        return redirect()->back()->with('success', 'Plan y prestaciones actualizados.');
    }

    public function destroy(AffiliatePlan $affiliatePlan)
    {
        // Validación manual de seguridad para asegurar que el plan pertenece a la empresa del usuario
        if ($affiliatePlan->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $affiliatePlan->delete();
        return redirect()->back()->with('success', 'Plan eliminado.');
    }
}
