<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Client;
use App\Models\Hospitalization;
use App\Models\HospitalizationBed;
use App\Models\HospitalizationAdministration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Clase HospitalizationController
 * Gestiona el flujo clínico y operativo de las internaciones de la clínica (inquilino).
 */
class HospitalizationController extends Controller
{
    /**
     * Dashboard / Mapa de Camas en tiempo real.
     */
    public function dashboard()
    {
        $empresaId = Auth::user()->empresa_id;

        // Cargar todas las habitaciones de la empresa con sus camas
        $rooms = Room::where('empresa_id', $empresaId)
            ->where('activo', true)
            ->with(['beds' => function ($query) {
                $query->orderBy('nombre', 'asc');
            }])
            ->orderBy('nombre', 'asc')
            ->get();

        // Obtener internaciones activas (con paciente y su asignación de cama actual)
        $activeHospitalizations = Hospitalization::where('empresa_id', $empresaId)
            ->where('estado', 'activo')
            ->with(['patient', 'currentBedAssignment.bed.room'])
            ->get();

        // Mapear internaciones activas para un acceso ultra rápido por cama_id en la vista
        $occupiedBeds = [];
        foreach ($activeHospitalizations as $h) {
            if ($h->currentBedAssignment) {
                $occupiedBeds[$h->currentBedAssignment->cama_id] = $h;
            }
        }

        return view('empresa.hospitalization.dashboard', compact('rooms', 'occupiedBeds'));
    }

    /**
     * Listado general e historial de internaciones.
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $search = $request->get('search');

        $query = Hospitalization::where('empresa_id', $empresaId)
            ->with(['patient', 'currentBedAssignment.bed.room'])
            ->orderBy('fecha_ingreso', 'desc');

        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('cuit_cuil', 'like', '%' . $search . '%')
                  ->orWhere('telefono', 'like', '%' . $search . '%');
            });
        }

        $hospitalizations = $query->paginate(15);

        return view('empresa.hospitalization.index', compact('hospitalizations', 'search'));
    }

    /**
     * Formulario de ingreso / admisión a internación.
     */
    public function create(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $preselectedCamaId = $request->get('cama_id');

        // Obtener todos los pacientes/afiliados de la empresa
        $patients = Client::where('empresa_id', $empresaId)
            ->orderBy('nombre', 'asc')
            ->get();

        // Obtener camas disponibles
        $availableBeds = Bed::where('empresa_id', $empresaId)
            ->where('estado', 'libre')
            ->whereHas('room', function ($query) {
                $query->where('activo', true);
            })
            ->with('room')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('empresa.hospitalization.create', compact('patients', 'availableBeds', 'preselectedCamaId'));
    }

    /**
     * Guardar una nueva internación clínica.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'cama_id' => 'required|exists:camas,id',
            'fecha_ingreso' => 'required|date',
            'motivo_ingreso' => 'required|string',
            'diagnostico_ingreso' => 'required|string',
        ]);

        $empresaId = Auth::user()->empresa_id;

        // Iniciar transacción de base de datos para asegurar consistencia
        DB::beginTransaction();

        try {
            // Validar propiedad e integridad del paciente y la cama
            $patient = Client::where('id', $request->client_id)
                ->where('empresa_id', $empresaId)
                ->firstOrFail();

            $bed = Bed::where('id', $request->cama_id)
                ->where('empresa_id', $empresaId)
                ->with('room')
                ->firstOrFail();

            if ($bed->estado !== 'libre') {
                return redirect()->back()->withInput()->with('error', 'La cama seleccionada ya no está disponible.');
            }

            // 1. Crear internación
            $hospitalization = Hospitalization::create([
                'empresa_id' => $empresaId,
                'client_id' => $patient->id,
                'fecha_ingreso' => $request->fecha_ingreso,
                'estado' => 'activo',
                'motivo_ingreso' => $request->motivo_ingreso,
                'diagnostico_ingreso' => $request->diagnostico_ingreso,
            ]);

            // 2. Ocupar la cama
            $bed->estado = 'ocupada';
            $bed->save();

            // 3. Registrar asignación de cama inicial
            HospitalizationBed::create([
                'internacion_id' => $hospitalization->id,
                'cama_id' => $bed->id,
                'fecha_desde' => $request->fecha_ingreso,
                'tipo_internacion' => $bed->room->tipo,
                'notas' => 'Ingreso inicial del paciente.',
            ]);

            DB::commit();

            return redirect()->route('empresa.hospitalization.dashboard')->with('success', 'Paciente ingresado correctamente a internación.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al procesar el ingreso: ' . $e->getMessage());
        }
    }

    /**
     * Ver ficha de seguimiento clínico (Dossier) de la internación.
     */
    public function dossier(Hospitalization $hospitalization)
    {
        if ($hospitalization->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        // Cargar relaciones
        $hospitalization->load(['patient', 'assignments.bed.room', 'administrations.user']);

        $empresaId = Auth::user()->empresa_id;

        // Obtener camas disponibles para traslados
        $availableBeds = Bed::where('empresa_id', $empresaId)
            ->where('estado', 'libre')
            ->whereHas('room', function ($q) {
                $q->where('activo', true);
            })
            ->with('room')
            ->get();

        // Obtener historial de fichadas presenciales QR de cama
        $presenceLogs = \App\Models\BedPresenceLog::where('internacion_id', $hospitalization->id)
            ->with(['bed.room', 'user'])
            ->orderBy('scanned_at', 'desc')
            ->get();

        return view('empresa.hospitalization.dossier', compact('hospitalization', 'availableBeds', 'presenceLogs'));
    }

    /**
     * Realizar traslado de cama / sector del paciente.
     */
    public function transfer(Request $request, Hospitalization $hospitalization)
    {
        if ($hospitalization->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'cama_id' => 'required|exists:camas,id',
            'fecha_traslado' => 'required|date',
            'notas' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $empresaId = Auth::user()->empresa_id;
            $newBed = Bed::where('id', $request->cama_id)
                ->where('empresa_id', $empresaId)
                ->with('room')
                ->firstOrFail();

            if ($newBed->estado !== 'libre') {
                return redirect()->back()->with('error', 'La cama seleccionada para el traslado ya no está libre.');
            }

            // 1. Finalizar asignación de cama anterior
            $currentAssignment = $hospitalization->currentBedAssignment;
            if ($currentAssignment) {
                $currentAssignment->fecha_hasta = $request->fecha_traslado;
                $currentAssignment->save();

                // Liberar cama anterior
                $oldBed = $currentAssignment->bed;
                $oldBed->estado = 'libre';
                $oldBed->save();
            }

            // 2. Ocupar nueva cama
            $newBed->estado = 'ocupada';
            $newBed->save();

            // 3. Crear nuevo registro de asignación/traslado
            HospitalizationBed::create([
                'internacion_id' => $hospitalization->id,
                'cama_id' => $newBed->id,
                'fecha_desde' => $request->fecha_traslado,
                'tipo_internacion' => $newBed->room->tipo,
                'notas' => $request->notas ?? 'Traslado de sector/habitación.',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'El paciente fue trasladado con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error en el proceso de traslado: ' . $e->getMessage());
        }
    }

    /**
     * Registrar administración de medicamentos, descartables o signos vitales.
     */
    public function storeAdministration(Request $request, Hospitalization $hospitalization)
    {
        if ($hospitalization->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'tipo' => 'required|in:medicamento,descartable,control_signos,visita,otro',
            'nombre' => 'required|string|max:150',
            'cantidad' => 'required|numeric|min:0.01',
            'dosis' => 'nullable|string|max:100',
            'frecuencia' => 'nullable|string|max:100',
            'via' => 'nullable|string|max:50',
            'precio_unitario' => 'nullable|numeric|min:0',
            'fecha_registro' => 'required|date',
            'proxima_aplicacion' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);

        HospitalizationAdministration::create([
            'internacion_id' => $hospitalization->id,
            'user_id' => Auth::user()->id,
            'tipo' => $request->tipo,
            'nombre' => $request->nombre,
            'cantidad' => $request->cantidad,
            'dosis' => $request->dosis,
            'frecuencia' => $request->frecuencia,
            'via' => $request->via,
            'precio_unitario' => $request->precio_unitario,
            'fecha_registro' => $request->fecha_registro,
            'proxima_aplicacion' => $request->proxima_aplicacion,
            'notas' => $request->notas,
        ]);

        return redirect()->back()->with('success', 'Registro clínico cargado correctamente.');
    }

    /**
     * Registrar el alta médica y finalizar la internación.
     */
    public function discharge(Request $request, Hospitalization $hospitalization)
    {
        if ($hospitalization->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'fecha_egreso' => 'required|date',
            'diagnostico_egreso' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // 1. Cerrar internación
            $hospitalization->fecha_egreso = $request->fecha_egreso;
            $hospitalization->estado = 'alta';
            $hospitalization->diagnostico_egreso = $request->diagnostico_egreso;
            $hospitalization->save();

            // 2. Liberar la cama
            $currentAssignment = $hospitalization->currentBedAssignment;
            if ($currentAssignment) {
                $currentAssignment->fecha_hasta = $request->fecha_egreso;
                $currentAssignment->save();

                $bed = $currentAssignment->bed;
                $bed->estado = 'libre';
                $bed->save();
            }

            DB::commit();

            return redirect()->route('empresa.hospitalization.dashboard')->with('success', 'El alta médica del paciente ha sido registrada correctamente. Cama liberada.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el alta: ' . $e->getMessage());
        }
    }
}
