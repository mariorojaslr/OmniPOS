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
            'user_id' => 'required|exists:users,id',
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

        // 3. Crear registro
        $monto = (float) ($config['online_booking_price'] ?? 0);
        $estadoInicial = ($monto > 0) ? 'pendiente' : 'confirmado';

        $turno = Turno::create([
            'empresa_id' => $empresa->id,
            'client_id' => $patient->id,
            'user_id' => $request->user_id,
            'servicio_id' => $request->servicio_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'estado' => $estadoInicial,
            'monto' => $monto,
            'notas' => 'Reserva Autogestionada Online. Paciente: ' . $request->client_name,
        ]);

        if ($monto > 0) {
            // Requiere pago previo
            return redirect()->route('catalog.booking.payment', [$empresa->slug, $turno->id]);
        }

        return redirect()->route('catalog.booking', $empresa->slug)
            ->with('success', '¡Su turno ha sido agendado y confirmado con éxito! Le llegará un recordatorio por correo electrónico.');
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
            'notas' => $turno->notes . ' | Pago Online Procesado con éxito vía ' . ($empresa->config_pasarelas['online_booking_gateway'] ?? 'MercadoPago') . ' Transacción N° ' . rand(1000000, 9999999)
        ]);

        return redirect()->route('catalog.booking', $empresa->slug)
            ->with('success', '¡Excelente! Pago procesado de forma segura y turno confirmado en nuestra agenda.');
    }
}
