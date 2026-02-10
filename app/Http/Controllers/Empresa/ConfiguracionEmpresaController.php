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
        // ⚠️ Usar SIEMPRE el empresa_id del usuario autenticado
        $empresaId = auth()->user()->empresa_id;

        $config = DB::table('empresa_config')
            ->where('empresa_id', $empresaId)
            ->first();

        return view('empresa.configuracion.index', compact('config'));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */
    public function save(Request $request)
    {
        try {

            // ⚠️ Empresa siempre desde el usuario logueado
            $empresaId = auth()->user()->empresa_id;

            if (!$empresaId) {
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
                'logo'            => 'nullable|image|max:2048'
            ]);

            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */
            $logoPath = null;

            if ($request->hasFile('logo')) {

                // eliminar logo anterior si existe
                $oldLogo = DB::table('empresa_config')
                    ->where('empresa_id', $empresaId)
                    ->value('logo');

                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | DATOS
            |--------------------------------------------------------------------------
            */
            $data = [
                'empresa_id'      => $empresaId,
                'color_primary'   => $request->color_primary ?? '#1f6feb',
                'color_secondary' => $request->color_secondary ?? '#0d1117',
                'theme'           => $request->theme ?? 'light',
                'updated_at'      => now(),
            ];

            if ($logoPath) {
                $data['logo'] = $logoPath;
            }

            /*
            |--------------------------------------------------------------------------
            | UPSERT
            |--------------------------------------------------------------------------
            */
            DB::table('empresa_config')
                ->updateOrInsert(
                    ['empresa_id' => $empresaId],
                    $data
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
