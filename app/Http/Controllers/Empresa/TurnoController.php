<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\User;
use App\Models\Client;
use App\Models\Servicio;
use App\Models\AcuerdoProfesional;
use App\Models\ProfesionalConfig;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TurnoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        // --- ESTADÍSTICAS GLOBALES PARA BOTONES SUPERIORES ---
        $stats = [
            'realizados' => $empresa->turnos()->where('estado', 'confirmado')->count(),
            'pendientes' => $empresa->turnos()->where('estado', 'pendiente')->count(),
            'hoy'        => $empresa->turnos()->where('fecha', now()->format('Y-m-d'))->count(),
            'semana'     => $empresa->turnos()->whereBetween('fecha', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')])->count(),
            'mes'        => $empresa->turnos()->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->count(),
        ];

        // --- DEPARTAMENTOS CON CONTADORES ---
        $departamentos = $empresa->servicios()
            ->select('categoria', \DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->get();

        $turnos = $empresa->turnos()
            ->with('user', 'cliente', 'servicio')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->paginate(20);

        $profesionales = User::where('empresa_id', $empresa->id)->where('activo', true)->get();

        return view('empresa.turnos.index', compact('empresa', 'turnos', 'stats', 'departamentos', 'profesionales'));
    }

    public function events(Request $request)
    {
        $empresa = Auth::user()->empresa;
        $start = $request->get('start');
        $end = $request->get('end');

        $turnos = $empresa->turnos()
            ->with(['cliente.latestMedicalRecord', 'servicio', 'user'])
            ->whereBetween('fecha', [$start, $end])
            ->get();

        $events = $turnos->map(function($t) {
            // Paleta de colores premium por categoría
            $colors = [
                'Estética' => '#a78bfa', // Lavanda
                'Barbería' => '#1e293b', // Navy
                'Masajes' => '#10b981',  // Esmeralda
                'Peluquería' => '#f43f5e', // Rosa/Rojo
                'Uñas' => '#ec4899',     // Pink
                'default' => '#3b82f6'   // Azul
            ];

            $color = ($t->servicio && isset($colors[$t->servicio->categoria])) ? $colors[$t->servicio->categoria] : $colors['default'];

            return [
                'id' => $t->id,
                'title' => ($t->cliente ? $t->cliente->name : $t->cliente_nombre_manual) . ' - ' . ($t->servicio ? $t->servicio->nombre : 'Servicio'),
                'start' => $t->fecha . 'T' . $t->hora_inicio,
                'end' => $t->fecha . 'T' . $t->hora_fin,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'cliente' => $t->cliente ? $t->cliente->name : $t->cliente_nombre_manual,
                    'servicio' => $t->servicio ? $t->servicio->nombre : 'S/D',
                    'profesional' => $t->user ? $t->user->name : 'S/D',
                    'estado' => $t->estado,
                    'notas' => $t->notas,
                    'client_id' => $t->client_id,
                    'has_medical_history' => $t->cliente && $t->cliente->latestMedicalRecord ? true : false,
                    'clinical_json' => ($t->cliente && $t->cliente->latestMedicalRecord && $t->cliente->latestMedicalRecord->specialized_data)
                        ? json_encode($t->cliente->latestMedicalRecord->specialized_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                        : null
                ]
            ];
        });

        return response()->json($events);
    }

    public function create()
    {
        $empresa = Auth::user()->empresa;
        $servicios = $empresa->servicios()->where('activo', true)->get();
        $profesionales = User::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->with('profesionalConfig') // Cargamos la configuración para saber qué hacen
            ->get();
        $clientes = $empresa->clients()->orderBy('name')->get();
        $obrasSociales = \App\Models\ObraSocial::where('empresa_id', $empresa->id)->where('activo', true)->get();

        return view('empresa.turnos.create', compact('servicios', 'profesionales', 'clientes', 'obrasSociales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'cliente_nombre_manual' => 'required|string|max:255'
        ]);

        $empresa = Auth::user()->empresa;
        $servicio = Servicio::findOrFail($request->servicio_id);
        $profesional = User::find($request->user_id);
        $config = $profesional ? $profesional->profesionalConfig : null;

        // VALIDACIÓN DE ESPECIALIDAD (Evitar errores de asignación)
        if ($config) {
            $especialidades = is_array($config->especialidades) ? $config->especialidades : [];
            // Si tiene especialidades configuradas, verificamos que el servicio esté incluido
            if (!empty($especialidades) && !in_array($request->servicio_id, $especialidades)) {
                return redirect()->back()->withInput()->with('error', "El profesional {$profesional->name} no tiene autorizada la práctica: {$servicio->nombre}");
            }
        } else {
            // Si ni siquiera tiene configuración, avisamos
            return redirect()->back()->withInput()->with('error', "{$profesional->name} no está configurada como profesional. Por favor, completa su perfil en la sección de Usuarios.");
        }
        
        // --- VALIDACIÓN DE SOLAPAMIENTO (AVISO) ---
        $solapado = $empresa->turnos()
            ->where('user_id', $request->user_id)
            ->where('fecha', $request->fecha)
            ->where('hora_inicio', $request->hora_inicio)
            ->exists();

        // --- LÓGICA DE COMISIÓN ---
        $user_id = $request->user_id;
        $comision = 0;

        // 1. Prioridad: Acuerdo Particular
        $acuerdo = AcuerdoProfesional::where('user_id', $user_id)
            ->where('servicio_id', $servicio->id)
            ->first();

        if ($acuerdo) {
            if ($acuerdo->tipo_comision === 'porcentaje') {
                $comision = ($servicio->precio * $acuerdo->valor) / 100;
            } else {
                $comision = $acuerdo->valor;
            }
        } else {
            // 2. Prioridad: Configuración General del Profesional
            $profesionalConfig = ProfesionalConfig::where('user_id', $user_id)->first();
            
            if ($profesionalConfig && $profesionalConfig->tipo_comision) {
                if ($profesionalConfig->tipo_comision === 'porcentaje') {
                    $comision = ($servicio->precio * $profesionalConfig->valor_comision) / 100;
                } else {
                    $comision = $profesionalConfig->valor_comision;
                }
            } else {
                // 3. Fallback: Comisión por defecto del Servicio
                $comision = ($servicio->precio * ($servicio->comision_porcentaje ?? 0)) / 100;
            }
        }

        try {
            // --- CÁLCULO DE HORA FIN ---
            $duracion = $servicio->duracion_minutos ?? 30; // Fallback 30 min
            $hora_inicio = Carbon::parse($request->fecha . ' ' . $request->hora_inicio);
            $hora_fin = $hora_inicio->copy()->addMinutes($duracion)->format('H:i:s');

            $turno = $empresa->turnos()->create([
                'servicio_id' => $request->servicio_id,
                'user_id' => $request->user_id,
                'client_id' => $request->client_id, // Nueva vinculación
                'fecha' => $request->fecha,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $hora_fin, // Guardamos la hora de fin calculada
                'cliente_nombre_manual' => $request->cliente_nombre_manual,
                'monto' => $servicio->precio,
                'comision_monto' => $comision,
                'notas' => $request->notas,
                'obra_social_id' => $request->obra_social_id,
                'nro_afiliado_os' => $request->nro_afiliado_os,
                'estado' => 'pendiente'
            ]);

            $msg = 'Turno agendado correctamente.';
            if($solapado) $msg .= ' OJO: El profesional ya tiene otra actividad a esta hora.';

            return redirect()->route('empresa.turnos.index')->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar el turno: ' . $e->getMessage());
        }
    }

    public function checkout(Turno $turno)
    {
        // 1. Verificar si el turno ya fue cobrado
        if ($turno->venta_id) {
            return redirect()->route('empresa.turnos.index')->with('error', 'Este turno ya ha sido facturado.');
        }

        // 2. Si no tiene monto, asignar 0 o rechazar
        $monto = $turno->monto ?? 0;

        // 3. Crear una Venta en estado "Pendiente de Pago" o usar sesión para el POS
        // Como tenemos Venta Manual (Session), inyectamos los datos en la sesión para que el cajero los cobre
        $posData = [
            'origen' => 'turno',
            'turno_id' => $turno->id,
            'cliente_id' => $turno->client_id,
            'cliente_nombre' => $turno->client_id ? $turno->cliente->name : $turno->cliente_nombre_manual,
            'items' => [
                [
                    'descripcion' => 'Coseguro / Práctica: ' . ($turno->servicio ? $turno->servicio->nombre : 'Consulta'),
                    'cantidad' => 1,
                    'precio_unitario' => $monto,
                    'subtotal' => $monto,
                ]
            ],
            'total' => $monto
        ];

        session()->put('pos_cart', $posData);

        return redirect()->route('empresa.ventas.manual')->with('success', 'Turno cargado en caja. Por favor, procese el pago.');
    }

    /**
     * Pantalla de Configuración de Reserva y Pago Online.
     */
    public function onlineConfig()
    {
        $empresa = Auth::user()->empresa;
        $config = $empresa->config_pasarelas ?? [];
        return view('empresa.turnos.online_config', compact('empresa', 'config'));
    }

    /**
     * Guardar la Configuración de Reserva y Pago Online.
     */
    public function saveOnlineConfig(Request $request)
    {
        $empresa = Auth::user()->empresa;
        $config = $empresa->config_pasarelas ?? [];

        $config['online_booking_enabled'] = $request->has('online_booking_enabled');
        $config['online_booking_price'] = (float) $request->input('online_booking_price', 0);
        $config['online_booking_tolerance'] = $request->has('online_booking_tolerance');
        $config['online_booking_gateway'] = $request->input('online_booking_gateway', 'MercadoPago');
        $config['youtube_playlist_id_1'] = $request->input('youtube_playlist_id_1', 'PLUw7Nsql2TwQrUwKitNw4OIoxYAzEWczn');
        $config['youtube_playlist_id_2'] = $request->input('youtube_playlist_id_2', '');
        $config['youtube_playlist_id_3'] = $request->input('youtube_playlist_id_3', '');
        $config['youtube_playlist_id_4'] = $request->input('youtube_playlist_id_4', '');
        $config['youtube_playlist_id_5'] = $request->input('youtube_playlist_id_5', '');
        
        // Smart TV dynamic ticker messages
        $config['ticker_title_1'] = $request->input('ticker_title_1', '📇 Tenga su DNI Listo');
        $config['ticker_desc_1'] = $request->input('ticker_desc_1', 'Entregue su documento nacional de identidad en recepción para registrar su ingreso.');
        $config['ticker_title_2'] = $request->input('ticker_title_2', '📁 Digitalice sus Estudios');
        $config['ticker_desc_2'] = $request->input('ticker_desc_2', 'Presente radiografías, PDF o análisis previos a la secretaria para cargarlos de inmediato.');
        $config['ticker_title_3'] = $request->input('ticker_title_3', '🧼 Cuidado e Higiene');
        $config['ticker_desc_3'] = $request->input('ticker_desc_3', 'Utilice el dispenser de alcohol en gel disponible en los pasillos al entrar y salir.');

        $empresa->update([
            'config_pasarelas' => $config
        ]);

        return redirect()->route('empresa.turnos.online-config')->with('success', 'Configuración de reserva online guardada con éxito.');
    }
}
