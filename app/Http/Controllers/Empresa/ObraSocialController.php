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
}
