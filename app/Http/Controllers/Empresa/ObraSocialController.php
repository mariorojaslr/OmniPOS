<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObraSocial;
use Illuminate\Support\Facades\Auth;

class ObraSocialController extends Controller
{
    public function index()
    {
        $empresa = Auth::user()->empresa;
        $obrasSociales = ObraSocial::where('empresa_id', $empresa->id)->get();
        
        return view('empresa.obras_sociales.index', compact('obrasSociales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cuit' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'contacto_nombre' => 'nullable|string|max:150',
            'notas' => 'nullable|string',
        ]);

        $empresa = Auth::user()->empresa;

        ObraSocial::create([
            'empresa_id' => $empresa->id,
            'nombre' => $request->nombre,
            'cuit' => $request->cuit,
            'activo' => $request->has('activo'),
            'api_token' => \Illuminate\Support\Str::random(40),
            'config_json' => [
                'codigo_prestador' => $request->codigo_prestador ?? '',
                'direccion' => $request->direccion ?? '',
                'telefono' => $request->telefono ?? '',
                'email' => $request->email ?? '',
                'contacto_nombre' => $request->contacto_nombre ?? '',
                'notas' => $request->notas ?? '',
            ]
        ]);

        return redirect()->back()->with('success', 'Obra Social agregada correctamente.');
    }

    public function show(ObraSocial $obra_social)
    {
        // Chequeo manual de seguridad para asegurar el aislamiento de datos (Multi-tenancy)
        if ($obra_social->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        return redirect()->route('empresa.obras_sociales.index');
    }

    public function edit(ObraSocial $obra_social)
    {
        // Chequeo manual de seguridad para asegurar el aislamiento de datos (Multi-tenancy)
        if ($obra_social->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        return redirect()->route('empresa.obras_sociales.index');
    }

    public function update(Request $request, ObraSocial $obra_social)
    {
        // Chequeo manual de seguridad para asegurar el aislamiento de datos (Multi-tenancy)
        if ($obra_social->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'cuit' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'contacto_nombre' => 'nullable|string|max:150',
            'notas' => 'nullable|string',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'cuit' => $request->cuit,
            'activo' => $request->has('activo'),
            'config_json' => [
                'codigo_prestador' => $request->codigo_prestador ?? '',
                'direccion' => $request->direccion ?? '',
                'telefono' => $request->telefono ?? '',
                'email' => $request->email ?? '',
                'contacto_nombre' => $request->contacto_nombre ?? '',
                'notas' => $request->notas ?? '',
            ]
        ];

        if (!$obra_social->api_token) {
            $data['api_token'] = \Illuminate\Support\Str::random(40);
        }

        $obra_social->update($data);

        return redirect()->back()->with('success', 'Obra Social actualizada.');
    }

    public function destroy(ObraSocial $obra_social)
    {
        // Chequeo manual de seguridad para asegurar el aislamiento de datos (Multi-tenancy)
        if ($obra_social->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $obra_social->delete();
        
        return redirect()->back()->with('success', 'Obra Social eliminada.');
    }

    /**
     * Exporta la lista de Obras Sociales en formato CSV compatible con Excel.
     * Si no hay registros, funciona como plantilla vacía con los encabezados correspondientes.
     */
    public function exportExcel()
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=obras_sociales.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nombre', 'CUIT', 'Codigo Prestador', 'Direccion', 'Telefono', 'Email', 'Contacto Nombre', 'Notas'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            // Añadir el BOM para forzar a Excel a abrirlo con codificación UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, ';');

            $empresaId = auth()->user()->empresa_id;
            $items = ObraSocial::where('empresa_id', $empresaId)->get();

            foreach ($items as $item) {
                $codigo = $item->config_json['codigo_prestador'] ?? '';
                $direccion = $item->config_json['direccion'] ?? '';
                $telefono = $item->config_json['telefono'] ?? '';
                $email = $item->config_json['email'] ?? '';
                $contacto_nombre = $item->config_json['contacto_nombre'] ?? '';
                $notas = $item->config_json['notas'] ?? '';

                fputcsv($file, [
                    $item->nombre,
                    $item->cuit,
                    $codigo,
                    $direccion,
                    $telefono,
                    $email,
                    $contacto_nombre,
                    $notas
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Importa masivamente Obras Sociales desde un archivo CSV con soporte para delimitadores coma o punto y coma.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file'
        ]);

        $file = $request->file('excel_file');
        $path = $file->getRealPath();

        if (($handle = fopen($path, "r")) !== FALSE) {
            // Saltarse el BOM de UTF-8 si existe en el archivo
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }
            
            // Leer la cabecera
            $header = fgetcsv($handle, 1000, ";");
            if (count($header) <= 1) {
                rewind($handle);
                if ($bom === "\xEF\xBB\xBF") {
                    fread($handle, 3);
                }
                $header = fgetcsv($handle, 1000, ",");
                $separator = ",";
            } else {
                $separator = ";";
            }

            $empresaId = auth()->user()->empresa_id;

            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                if (empty($data[0])) continue; // Saltear líneas vacías o incompletas

                $nombre = trim($data[0]);
                $cuit = isset($data[1]) ? trim($data[1]) : '';
                $codigo = isset($data[2]) ? trim($data[2]) : '';
                $direccion = isset($data[3]) ? trim($data[3]) : '';
                $telefono = isset($data[4]) ? trim($data[4]) : '';
                $email = isset($data[5]) ? trim($data[5]) : '';
                $contacto_nombre = isset($data[6]) ? trim($data[6]) : '';
                $notas = isset($data[7]) ? trim($data[7]) : '';

                // Omitir cabeceras duplicadas
                if ($nombre === 'Nombre' || $nombre === 'nombre_completo') {
                    continue;
                }

                // Verificar si ya existe una obra social con el mismo nombre para esta empresa
                $exists = ObraSocial::where('empresa_id', $empresaId)
                    ->where('nombre', $nombre)
                    ->exists();

                if (!$exists && !empty($nombre)) {
                    ObraSocial::create([
                        'empresa_id' => $empresaId,
                        'nombre' => $nombre,
                        'cuit' => $cuit,
                        'activo' => true,
                        'api_token' => \Illuminate\Support\Str::random(40),
                        'config_json' => [
                            'codigo_prestador' => $codigo,
                            'direccion' => $direccion,
                            'telefono' => $telefono,
                            'email' => $email,
                            'contacto_nombre' => $contacto_nombre,
                            'notas' => $notas
                        ]
                    ]);
                }
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', 'Convenios de Obras Sociales importados correctamente.');
    }

    /**
     * Simula la validación de elegibilidad en tiempo real a través de API homologadora.
     */
    public function simulateEligibility(Request $request)
    {
        $request->validate([
            'obra_social_id' => 'required|exists:obras_sociales,id',
            'credential_number' => 'required|string|min:4|max:50',
        ]);

        $empresa = Auth::user()->empresa;
        $os = ObraSocial::where('empresa_id', $empresa->id)->findOrFail($request->obra_social_id);

        // Simulamos un retraso de red de 1.2 segundos para wowear visualmente con el loader
        usleep(1200000);

        $num = trim($request->credential_number);
        $status = 'APPROVED';
        $statusText = 'ACTIVO / HABILITADO';
        $message = 'El afiliado cuenta con cobertura vigente y activa en el plan seleccionado.';
        $coseguro = 0;
        $authNumber = 'AUT-' . rand(100000, 999999);
        $plan = 'Plan Standard Cobertura Plena';

        if (str_starts_with($num, '99')) {
            $status = 'REJECTED';
            $statusText = 'RECHAZADO / INACTIVO';
            $message = 'El afiliado se encuentra temporalmente inactivo o suspendido por mora administrativa.';
            $authNumber = 'N/A';
            $plan = 'N/A';
        } elseif (str_starts_with($num, '88')) {
            $status = 'REJECTED';
            $statusText = 'PLAN SIN COBERTURA';
            $message = 'El plan de este afiliado no cubre prestaciones ambulatorias en este centro médico.';
            $authNumber = 'N/A';
            $plan = 'Plan Básico (Co-pago Total)';
        } elseif (intval(substr($num, -1)) % 2 !== 0) {
            $status = 'COPAY';
            $statusText = 'HABILITADO CON CO-PAGO';
            $coseguro = 1500;
            $message = 'El afiliado requiere el pago de un coseguro fijo en recepción de $1.500.';
            $plan = 'Plan Oro / Co-pago Parcial';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'status_text' => $statusText,
            'obra_social' => $os->nombre,
            'api_token_used' => substr($os->api_token, 0, 8) . '****************************',
            'afiliado_cuit' => $os->cuit ?: 'No configurado',
            'coseguro' => $coseguro,
            'autorizacion' => $authNumber,
            'plan_afiliado' => $plan,
            'mensaje' => $message,
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
