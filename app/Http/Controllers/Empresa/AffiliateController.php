<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\AffiliateFee;
use App\Imports\AffiliateImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AffiliateController extends Controller
{
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;
        $affiliates = Client::where('empresa_id', $empresaId)
            ->where('is_affiliate', true)
            ->with(['obraSocial', 'affiliatePlan'])
            ->paginate(15);

        return view('empresa.affiliates.index', compact('affiliates'));
    }

    public function create()
    {
        $empresaId = Auth::user()->empresa_id;
        $plans = \App\Models\AffiliatePlan::where('empresa_id', $empresaId)->where('active', true)->get();
        $obrasSociales = \App\Models\ObraSocial::where('empresa_id', $empresaId)->where('activo', true)->get();
        $workplaces = \App\Models\Client::where('empresa_id', $empresaId)
            ->whereNotNull('workplace')
            ->where('workplace', '!=', '')
            ->distinct()
            ->pluck('workplace');
        return view('empresa.affiliates.create', compact('plans', 'obrasSociales', 'workplaces'));
    }

    public function store(Request $request)
    {
        // Aumentar el límite de tamaño de imagen a 20MB para admitir fotos pesadas de celulares
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'document' => 'required|string',
            'photo' => 'nullable|image|max:20480',
        ]);

        $data = $request->all();
        $data['empresa_id'] = Auth::user()->empresa_id;
        $data['is_affiliate'] = true;

        // Procesar Obras Sociales adicionales en JSON
        $additional = [];
        if ($request->has('additional_os')) {
            foreach ($request->additional_os as $key => $osId) {
                if ($osId) {
                    $additional[] = [
                        'obra_social_id' => (int) $osId,
                        'affiliate_number' => $request->additional_number[$key] ?? ''
                    ];
                }
            }
        }
        $data['additional_insurances'] = $additional;

        // Procesar y subir foto de perfil con auto-compresión inteligente
        if ($request->hasFile('photo')) {
            try {
                // Instanciar el gestor con el motor gráfico GD
                $manager = new ImageManager(new Driver());
                
                // Leer la imagen desde el archivo temporal
                $image = $manager->read($request->file('photo'));
                
                // Redimensionar con recorte centrado (cover) a 300x300 píxeles para mantener proporciones perfectas
                $image->cover(300, 300);
                
                // Codificar la imagen procesada a JPEG liviano (calidad del 80%) para ahorrar espacio
                $encoded = $image->toJpeg(80);
                
                // Generar un nombre de archivo único
                $filename = 'affiliates/' . uniqid() . '.jpg';
                
                try {
                    // Intentar guardar en Bunny.net (FTP)
                    \Storage::disk('bunny_storage')->put($filename, $encoded);
                    $data['photo_path'] = 'bunny:' . $filename;
                } catch (\Exception $e) {
                    // Fallback a almacenamiento local si Bunny falla
                    \Storage::disk('public')->put($filename, $encoded);
                    $data['photo_path'] = 'local:' . $filename;
                }
            } catch (\Exception $e) {
                // Fallback de emergencia si falla la librería de imágenes: guarda la imagen original directamente
                try {
                    $path = $request->file('photo')->store('affiliates', 'bunny_storage');
                    $data['photo_path'] = 'bunny:' . $path;
                } catch (\Exception $ex) {
                    $path = $request->file('photo')->store('affiliates', 'public');
                    $data['photo_path'] = 'local:' . $path;
                }
            }
        }

        Client::create($data);

        return redirect()->route('empresa.affiliates.index')->with('success', 'Afiliado registrado correctamente.');
    }

    public function show(Client $affiliate)
    {
        $affiliate->load(['obraSocial', 'affiliatePlan', 'medicalRecords']);
        return view('empresa.affiliates.show', compact('affiliate'));
    }

    public function edit(Client $affiliate)
    {
        $empresaId = Auth::user()->empresa_id;
        $plans = \App\Models\AffiliatePlan::where('empresa_id', $empresaId)->where('active', true)->get();
        $obrasSociales = \App\Models\ObraSocial::where('empresa_id', $empresaId)->where('activo', true)->get();
        $workplaces = \App\Models\Client::where('empresa_id', $empresaId)
            ->whereNotNull('workplace')
            ->where('workplace', '!=', '')
            ->distinct()
            ->pluck('workplace');
        return view('empresa.affiliates.edit', compact('affiliate', 'plans', 'obrasSociales', 'workplaces'));
    }

    public function update(Request $request, Client $affiliate)
    {
        // Aumentar el límite a 20MB para admitir fotos grandes tomadas por smartphones modernos
        $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|string',
            'photo' => 'nullable|image|max:20480',
        ]);

        $data = $request->all();

        // Procesar Obras Sociales adicionales en JSON
        $additional = [];
        if ($request->has('additional_os')) {
            foreach ($request->additional_os as $key => $osId) {
                if ($osId) {
                    $additional[] = [
                        'obra_social_id' => (int) $osId,
                        'affiliate_number' => $request->additional_number[$key] ?? ''
                    ];
                }
            }
        }
        $data['additional_insurances'] = $additional;

        // Subir y procesar la nueva foto si el usuario cargó una
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existiera para no dejar archivos huérfanos
            if ($affiliate->photo_path) {
                try {
                    if (str_starts_with($affiliate->photo_path, 'bunny:')) {
                        \Storage::disk('bunny_storage')->delete(str_replace('bunny:', '', $affiliate->photo_path));
                    } elseif (str_starts_with($affiliate->photo_path, 'local:')) {
                        \Storage::disk('public')->delete(str_replace('local:', '', $affiliate->photo_path));
                    } else {
                        \Storage::disk('bunny_storage')->delete($affiliate->photo_path);
                    }
                } catch (\Exception $e) {
                    // Ignorar errores al borrar
                }
            }

            try {
                // Instanciar el gestor con el driver GD
                $manager = new ImageManager(new Driver());
                
                // Leer la imagen subida
                $image = $manager->read($request->file('photo'));
                
                // Ajustar al centro a 300x300 píxeles recortando proporcionalmente (cover)
                $image->cover(300, 300);
                
                // Codificar a JPEG ultra liviano
                $encoded = $image->toJpeg(80);
                
                // Generar un nombre de archivo único
                $filename = 'affiliates/' . uniqid() . '.jpg';
                
                try {
                    // Guardar en Bunny.net
                    \Storage::disk('bunny_storage')->put($filename, $encoded);
                    $data['photo_path'] = 'bunny:' . $filename;
                } catch (\Exception $e) {
                    // Fallback a almacenamiento local público
                    \Storage::disk('public')->put($filename, $encoded);
                    $data['photo_path'] = 'local:' . $filename;
                }
            } catch (\Exception $e) {
                // Fallback de emergencia si falla la librería gráfica
                try {
                    $path = $request->file('photo')->store('affiliates', 'bunny_storage');
                    $data['photo_path'] = 'bunny:' . $path;
                } catch (\Exception $ex) {
                    $path = $request->file('photo')->store('affiliates', 'public');
                    $data['photo_path'] = 'local:' . $path;
                }
            }
        }

        $affiliate->update($data);

        return redirect()->route('empresa.affiliates.index')->with('success', 'Datos del afiliado actualizados.');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new AffiliateImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'Afiliados importados correctamente.');
    }

    public function generateFees(Request $request)
    {
        $request->validate([
            'period' => 'required|string', // Ej: 2026-06
            'due_date' => 'required|date',
        ]);

        $empresaId = Auth::user()->empresa_id;
        $affiliates = Client::where('empresa_id', $empresaId)
            ->where('is_affiliate', true)
            ->where('affiliate_status', 'active')
            ->get();

        $count = 0;
        foreach ($affiliates as $aff) {
            // Evitar duplicados para el mismo periodo
            $exists = AffiliateFee::where('client_id', $aff->id)
                ->where('period', $request->period)
                ->exists();

            if (!$exists) {
                AffiliateFee::create([
                    'empresa_id' => $empresaId,
                    'client_id' => $aff->id,
                    'period' => $request->period,
                    'amount' => $aff->monthly_fee,
                    'due_date' => $request->due_date,
                    'status' => 'pending',
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "Se generaron $count cuotas para el periodo {$request->period}.");
    }

    public function accountStatement(Client $client)
    {
        $fees = AffiliateFee::where('client_id', $client->id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->orderByDesc('period')
            ->get();

        return view('empresa.affiliates.account_statement', compact('client', 'fees'));
    }

    /**
     * Exporta la lista de afiliados en formato CSV alineado al importador.
     * Si no hay registros, funciona como plantilla vacía para el usuario.
     */
    public function exportExcel()
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=afiliados.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Columnas alineadas exactamente con lo que el importador AffiliateImport espera
        $columns = ['nombre_completo', 'documento', 'email', 'telefono', 'lugar_trabajo', 'nro_afiliado_os', 'cuota_mensual'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            // Forzar BOM UTF-8 para Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, ';');

            $empresaId = Auth::user()->empresa_id;
            $items = Client::where('empresa_id', $empresaId)->where('is_affiliate', true)->get();

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->name,
                    $item->document,
                    $item->email,
                    $item->phone,
                    $item->workplace,
                    $item->affiliate_number,
                    $item->monthly_fee
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
