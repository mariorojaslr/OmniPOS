<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Product;
use App\Models\User;
use App\Models\Client;
use App\Models\Servicio;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Mostrar catálogo público de una empresa
     * Con filtros:
     * - mas_vendidos
     * - nuevos
     * - promociones
     */
    public function index(Request $request, Empresa $empresa)
    {
        // Redireccionar automáticamente al portal de turnos si está habilitado (evitando el antiguo catálogo e-commerce)
        $config = $empresa->config_pasarelas ?? [];
        if (!empty($config['online_booking_enabled'])) {
            return redirect()->route('catalog.booking', $empresa->slug);
        }

        // Base: productos activos de la empresa
        $query = Product::where('empresa_id', $empresa->id)
            ->where('active', true)
            ->with('images');

        /**
         * =============================
         * FILTROS DEL CATALOGO
         * =============================
         */
        $filtro = $request->get('filtro');

        switch ($filtro) {

            /**
             * Más vendidos
             * Requiere campo: total_vendido (si no existe, luego lo agregamos bien)
             */
            case 'mas_vendidos':
                if (\Schema::hasColumn('products', 'total_vendido')) {
                    $query->orderByDesc('total_vendido');
                }
                break;

            /**
             * Nuevos productos
             */
            case 'nuevos':
                $query->orderByDesc('created_at');
                break;

            /**
             * Promociones
             * Requiere campo: en_promocion o precio_oferta (si no existe lo creamos bien)
             */
            case 'promociones':
                if (\Schema::hasColumn('products', 'en_promocion')) {
                    $query->where('en_promocion', true);
                }
                break;
        }

        $products = $query->paginate(24)->withQueryString();

        return view('catalog.index', compact('empresa', 'products', 'filtro'));
    }

    /**
     * Mostrar producto individual
     */
    public function show(Empresa $empresa, Product $product)
    {
        // Seguridad multiempresa
        if ($product->empresa_id !== $empresa->id) {
            abort(404);
        }

        $product->load(['images', 'variants', 'videos']);

        return view('catalog.show', compact('empresa', 'product'));
    }

    /**
     * Portal de Reserva de Turnos Online para el Paciente.
     */
    public function bookingPortal(Request $request, Empresa $empresa)
    {
        $config = $empresa->config_pasarelas ?? [];
        if (empty($config['online_booking_enabled'])) {
            return redirect()->route('catalog.index', $empresa->slug)
                ->with('error', 'El portal autogestionado de turnos online no está habilitado para esta clínica.');
        }

        // Cargar profesionales
        $doctors = User::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->with('profesionalConfig')
            ->get();

        // Cargar servicios/consultas
        $services = Servicio::where('empresa_id', $empresa->id)->get();

        return view('catalog.booking_portal', compact('empresa', 'doctors', 'services', 'config'));
    }

    /**
     * Confirmar y Guardar Pre-reserva de Turno.
     */
    public function confirmBooking(Request $request, Empresa $empresa)
    {
        $config = $empresa->config_pasarelas ?? [];
        if (empty($config['online_booking_enabled'])) {
            abort(403, 'Portal no activo');
        }

        $request->validate([
            'client_name' => 'required|string|max:191',
            'client_document' => 'required|string|max:20',
            'client_email' => 'required|email|max:191',
            'client_phone' => 'required|string|max:30',
            'user_id' => 'required',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|string',
        ]);

        // 1. Obtener o crear al paciente de forma automática y transparente
        $patient = Client::firstOrCreate(
            [
                'empresa_id' => $empresa->id,
                'document' => $request->client_document
            ],
            [
                'name' => $request->client_name,
                'email' => $request->client_email,
                'phone' => $request->client_phone,
            ]
        );

        // 2. Calcular duraciones de slot (30 minutos estandarizados)
        $horaInicio = $request->hora;
        $horaFin = Carbon::parse($request->hora)->addMinutes(30)->format('H:i');

        // LÓGICA ANTI-OVERBOOKING Y "CUALQUIER MÉDICO"
        $finalDoctorId = $request->user_id;

        if ($finalDoctorId === 'any') {
            $doctoresActivos = User::where('empresa_id', $empresa->id)->where('activo', true)->pluck('id');
            $ocupados = Turno::where('empresa_id', $empresa->id)
                ->where('fecha', $request->fecha)
                ->where('hora_inicio', $horaInicio)
                ->whereIn('estado', ['confirmado', 'pendiente'])
                ->pluck('user_id');
            
            $libres = $doctoresActivos->diff($ocupados);
            if ($libres->isEmpty()) {
                return redirect()->back()->with('error', 'Lo sentimos, este turno acaba de ser tomado por otra persona. Por favor seleccione otro horario.');
            }
            $finalDoctorId = $libres->first();
        } else {
            $existe = Turno::where('empresa_id', $empresa->id)
                ->where('user_id', $finalDoctorId)
                ->where('fecha', $request->fecha)
                ->where('hora_inicio', $horaInicio)
                ->whereIn('estado', ['confirmado', 'pendiente'])
                ->exists();
                
            if ($existe) {
                return redirect()->back()->with('error', 'Lo sentimos, este turno acaba de ser tomado por otra persona. Por favor seleccione otro horario.');
            }
        }

        // 3. Crear registro
        $monto = (float) ($config['online_booking_price'] ?? 0);
        $estadoInicial = ($monto > 0) ? 'pendiente' : 'confirmado';

        $turno = Turno::create([
            'empresa_id' => $empresa->id,
            'client_id' => $patient->id,
            'user_id' => $finalDoctorId,
            'servicio_id' => $request->servicio_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'estado' => $estadoInicial,
            'monto' => $monto,
            'notas' => 'Reserva Autogestionada Online. Paciente: ' . $request->client_name,
        ]);

        if ($monto > 0) {
            return redirect()->route('catalog.booking.payment', [$empresa->slug, $turno->id]);
        }

        $wspMsg = '¡Turno agendado con éxito! Podés realizar el seguimiento de tu turno en tiempo real aquí.';
        return redirect()->route('turno.seguimiento', $turno->id)->with('success', $wspMsg);
    }

    /**
     * Pantalla de pago del turno.
     */
    public function bookingPayment(Empresa $empresa, Turno $turno)
    {
        // Seguridad
        if ($turno->empresa_id !== $empresa->id) {
            abort(404);
        }

        $turno->load(['cliente', 'user', 'servicio']);
        $config = $empresa->config_pasarelas ?? [];

        return view('catalog.booking_payment', compact('empresa', 'turno', 'config'));
    }

    /**
     * Procesar pago de turno.
     */
    public function bookingPaymentProcess(Request $request, Empresa $empresa, Turno $turno)
    {
        if ($turno->empresa_id !== $empresa->id) {
            abort(404);
        }

        // Simulación exitosa de pasarela
        $turno->update([
            'estado' => 'confirmado',
            'notas' => ($turno->notas ?? '') . ' | Pago Online Simulado con éxito vía ' . ($empresa->config_pasarelas['online_booking_gateway'] ?? 'MercadoPago') . ' Transacción N° ' . rand(1000000, 9999999)
        ]);

        $wspMsg = '¡Excelente! Pago simulado con éxito. Tu turno ha sido confirmado.';
        return redirect()->route('turno.seguimiento', $turno->id)
            ->with('success', $wspMsg);
    }

    /**
     * Obtener slots horarios ocupados para un médico y una fecha.
     */
    public function occupiedSlots(Request $request, Empresa $empresa)
    {
        $doctorId = $request->get('doctor_id');
        $fecha = $request->get('fecha');

        if (!$doctorId || !$fecha) {
            return response()->json(['success' => false, 'error' => 'Parámetros faltantes']);
        }

        if ($doctorId === 'any') {
            $totalDoctores = User::where('empresa_id', $empresa->id)->where('activo', true)->count();
            
            $ocupaciones = Turno::where('empresa_id', $empresa->id)
                ->where('fecha', $fecha)
                ->whereIn('estado', ['confirmado', 'pendiente'])
                ->selectRaw('hora_inicio, count(*) as cant')
                ->groupBy('hora_inicio')
                ->pluck('cant', 'hora_inicio');
                
            $ocupados = [];
            foreach ($ocupaciones as $hora => $cant) {
                if ($cant >= $totalDoctores) {
                    $ocupados[] = Carbon::parse($hora)->format('H:i');
                }
            }
        } else {
            // Obtener todos los turnos confirmados o pendientes para ese doctor y esa fecha en la empresa
            $ocupados = Turno::where('empresa_id', $empresa->id)
                ->where('user_id', $doctorId)
                ->where('fecha', $fecha)
                ->whereIn('estado', ['confirmado', 'pendiente'])
                ->pluck('hora_inicio')
                ->map(function($hora) {
                    return Carbon::parse($hora)->format('H:i');
                })
                ->toArray();
        }

        return response()->json([
            'success' => true,
            'ocupados' => $ocupados
        ]);
    }

    /**
     * Portal Público Móvil de Seguimiento de Turno para el Paciente
     */
    public function patientTurnoTracking($id)
    {
        $turno = Turno::with(['empresa.config', 'cliente', 'user', 'servicio'])->findOrFail($id);
        $empresa = $turno->empresa;

        return view('catalog.turno_tracking', compact('turno', 'empresa'));
    }

    /**
     * Confirmación de Asistencia directa por parte del Paciente
     */
    public function patientConfirmAttendance($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->estado = 'confirmado';
        $turno->save();

        return back()->with('success', '¡Excelente! Tu turno ha sido confirmado. Te esperamos puntualmente.');
    }
}
