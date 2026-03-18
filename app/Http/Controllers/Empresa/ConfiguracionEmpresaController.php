<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConfiguracionEmpresaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PANTALLA CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = auth()->user();
        $empresa = $user->empresa;
        $config = $empresa->config;

        return view('empresa.configuracion.index', compact('config', 'empresa'));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */
    public function save(Request $request)
    {
        try {
            $user = auth()->user();
            $empresa = $user->empresa;

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Empresa no identificada'
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDACIÓN
            |--------------------------------------------------------------------------
            */
            $request->validate([
                'color_primary'   => 'nullable|string|max:20',
                'color_secondary' => 'nullable|string|max:20',
                'theme'           => 'nullable|in:light,dark',
                'logo'            => 'nullable|image|max:2048',
                'dias_nuevo'      => 'nullable|integer|min:1|max:365',
                
                // Fiscales / ARCA
                'arca_cuit'            => 'nullable|string|max:20',
                'condicion_iva'        => 'nullable|string|max:100',
                'iibb'                 => 'nullable|string|max:50',
                'arca_punto_venta'     => 'nullable|integer',
                'proximo_numero_factura' => 'nullable|integer|min:1',
                'direccion_fiscal'     => 'nullable|string|max:255',
                'dia_cierre_periodo'   => 'nullable|integer|min:0|max:31',
                'arca_ambiente'        => 'nullable|string|in:homologacion,produccion',
                'arca_certificado'     => 'nullable|file|max:2048',
                'arca_llave'           => 'nullable|file|max:2048',
                
                'pasarelas'            => 'nullable|array',
            ]);

            /*
            |--------------------------------------------------------------------------
            | MANEJO DE ARCHIVOS (Certificados ARCA)
            |--------------------------------------------------------------------------
            */
            $updateData = [
                'arca_cuit'          => $request->arca_cuit,
                'condicion_iva'      => $request->condicion_iva,
                'iibb'               => $request->iibb,
                'arca_punto_venta'   => $request->arca_punto_venta ?? 1,
                'proximo_numero_factura' => $request->proximo_numero_factura ?? 1,
                'direccion_fiscal'   => $request->direccion_fiscal,
                'dia_cierre_periodo' => $request->dia_cierre_periodo ?? 0,
                'arca_ambiente'      => $request->arca_ambiente ?? 'homologacion',
                'config_pasarelas'   => $request->pasarelas ?? [],
            ];

            if ($request->hasFile('arca_certificado')) {
                $updateData['arca_certificado'] = $request->file('arca_certificado')->store('certificates', 'local');
            }
            if ($request->hasFile('arca_llave')) {
                $updateData['arca_llave'] = $request->file('arca_llave')->store('certificates', 'local');
            }

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR EMPRESA
            |--------------------------------------------------------------------------
            */
            $empresa->update($updateData);

            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $config = $empresa->config;
                if ($config && $config->logo && Storage::disk('public')->exists($config->logo)) {
                    Storage::disk('public')->delete($config->logo);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR CONFIG (VISUAL)
            |--------------------------------------------------------------------------
            */
            $configData = [
                'color_primary'   => $request->color_primary ?? '#1f6feb',
                'color_secondary' => $request->color_secondary ?? '#0d1117',
                'theme'           => $request->theme ?? 'light',
                'dias_nuevo'      => $request->dias_nuevo ?? 7,
            ];

            if ($logoPath) {
                $configData['logo'] = $logoPath;
            }

            $empresa->config()->updateOrCreate(
                ['empresa_id' => $empresa->id],
                $configData
            );

            return response()->json([
                'success' => true,
                'message' => 'Configuración guardada correctamente'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
