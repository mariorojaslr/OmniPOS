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

        $globalSettings = \App\Models\SystemSetting::where('key', 'afip_tutorial_video')->first();
        $afipVideoId = $globalSettings->value ?? 'v6r4D3Ljuy8';

        return view('empresa.configuracion.index', compact('config', 'empresa', 'afipVideoId'));
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
                'mod_orden_pedido' => 'nullable|boolean',
                'mod_orden_pedido_extra' => 'nullable|boolean',
                'mod_turnos' => 'nullable|boolean',
                'mod_unidades_medida' => 'nullable|boolean',
                
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
                'arca_activo'        => $request->has('arca_activo'),
                'config_pasarelas'   => $request->pasarelas ?? [],
            ];

            if ($request->hasFile('arca_certificado')) {
                $file = $request->file('arca_certificado');
                $certName = 'empresa_' . $empresa->id . '_cert.crt';
                $file->move(base_path('ARCA'), $certName);
                $updateData['arca_certificado'] = 'ARCA/' . $certName;
            }
            if ($request->hasFile('arca_llave')) {
                $file = $request->file('arca_llave');
                $keyName = 'empresa_' . $empresa->id . '_key.key';
                $file->move(base_path('ARCA'), $keyName);
                $updateData['arca_llave'] = 'ARCA/' . $keyName;
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
            | ACTUALIZAR CONFIG (VISUAL Y MÓDULOS)
            |--------------------------------------------------------------------------
            */
            $configData = [
                'color_primary'   => $request->color_primary ?? '#1f6feb',
                'color_secondary' => $request->color_secondary ?? '#0d1117',
                'theme'           => $request->theme ?? 'light',
                'dias_nuevo'      => $request->dias_nuevo ?? 7,
                'mod_orden_pedido' => $request->has('mod_orden_pedido'),
                'mod_orden_pedido_extra' => $request->has('mod_orden_pedido_extra'),
                'mod_turnos' => $request->has('mod_turnos'),
                'mod_unidades_medida' => $request->has('mod_unidades_medida'),
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

    /*
    |--------------------------------------------------------------------------
    | TEST AFIP CONNECTION
    |--------------------------------------------------------------------------
    */
    public function testAfip()
    {
        try {
            $user = auth()->user();
            $empresa = $user->empresa;

            if (!$empresa->arca_cuit) {
                throw new \Exception("Debes configurar el CUIT del titular.");
            }

            $cuit = str_replace('-', '', $empresa->arca_cuit);

            $resFolder = storage_path('app/afip_res/');
            if (!file_exists($resFolder)) {
                mkdir($resFolder, 0777, true);
            }

            // Forzar las rutas a la carpeta ARCA de la raíz
            $certPathOnDisk = base_path('ARCA/empresa_' . $empresa->id . '_cert.crt');
            $keyPathOnDisk = base_path('ARCA/empresa_' . $empresa->id . '_key.key');

            if (!file_exists($certPathOnDisk) || !file_exists($keyPathOnDisk)) {
                throw new \Exception("No se encontraron los archivos en la carpeta ARCA del servidor. Asegúrate de subirlos con los nombres correctos.");
            }

            // Preparar AFIP SDK leyendo el contenido directamente
            $afipConfig = [
                'CUIT' => (int) $cuit,
                'production' => ($empresa->arca_ambiente === 'produccion'),
                'cert' => file_get_contents($certPathOnDisk),
                'key' => file_get_contents($keyPathOnDisk),
                'res_folder' => $resFolder,
                'access_token' => env('AFIP_ACCESS_TOKEN', ''),
            ];
            
            $afip = new \Afip($afipConfig);

            // Verificamos conexión consultando el estado del servidor de facturación
            $status = $afip->ElectronicBilling->GetServerStatus();
            
            if (!isset($status->AppServer) || $status->AppServer !== 'OK') {
                throw new \Exception("Los servidores de facturación de AFIP no responden correctamente.");
            }

            // Además, podríamos intentar obtener el último comprobante para probar que el Punto de Venta coincide.
            $ptoVenta = $empresa->arca_punto_venta ?? 12;
            $tipoComp = ($empresa->condicion_iva == 'Monotributista') ? 11 : 6; // C (11) o B (6)
            
            $ultimoComprobante = null;
            $warning = null;
            try {
                $ultimoComprobante = $afip->ElectronicBilling->GetLastVoucher($ptoVenta, $tipoComp);
            } catch (\Exception $e) {
                // Si tira error aquí, significa que la AFIP lo rechazó por alguna inconsistencia, 
                // PERO la conexión fue exitosa! Capturamos esto.
                $warning = "Conexión lograda, pero AFIP devolvió una observación: " . $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'message' => '¡Autenticación con AFIP Exitosa! Tus certificados funcionan de maravilla.',
                'warning' => $warning,
                'ultimo_comprobante' => $ultimoComprobante
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔍 Buscar datos en AFIP por CUIT (Padron)
     */
    public function searchByCuit(Request $request)
    {
        try {
            $cuit = $request->input('cuit');
            if (empty($cuit)) throw new \Exception("CUIT requerido");

            $empresa = auth()->user()->empresa;
            
            // Forzar las rutas a la carpeta ARCA de la raíz
            $certPathOnDisk = base_path('ARCA/empresa_' . $empresa->id . '_cert.crt');
            $keyPathOnDisk  = base_path('ARCA/empresa_' . $empresa->id . '_key.key');
            $taPath         = storage_path('app/ARCA/ta');

            if (!file_exists($certPathOnDisk) || !file_exists($keyPathOnDisk)) {
                throw new \Exception("Certificados no encontrados en ARCA (empresa_{$empresa->id}).");
            }

            if(!is_dir($taPath)) mkdir($taPath, 0775, true);

            // 🚀 USAR LÓGICA DIRECTA (BYPASS AFIPSDK)
            $afipManual = new \Afip([
                'CUIT'         => (int) str_replace('-', '', $empresa->arca_cuit),
                'production'   => ($empresa->arca_ambiente === 'produccion'),
                'cert'         => $certPathOnDisk,
                'key'          => $keyPathOnDisk,
                'ta_folder'    => $taPath,
                'force_soap'   => true,
                'access_token' => env('AFIP_ACCESS_TOKEN', ''),
            ]);

            // Intentar con el servicio más común
            try {
                $res = $afipManual->RegisterScopeTen->GetTaxpayerDetails((int) str_replace('-', '', $cuit));
            } catch (\Exception $e) {
                // Fallback al 5
                try {
                    $res = $afipManual->RegisterScopeFive->GetTaxpayerDetails((int) str_replace('-', '', $cuit));
                } catch (\Exception $e2) {
                    throw new \Exception("AFIP: " . $e->getMessage());
                }
            }

            if (!$res) throw new \Exception("AFIP no devolvió información para el CUIT $cuit");

            // AFIP puede devolver nombre/apellido o razonSocial según el tipo de contribuyente
            $nombre = trim(($res->apellido ?? '') . ' ' . ($res->nombre ?? ''));
            if(empty($nombre)) $nombre = $res->razonSocial ?? 'Contribuyente Desconocido';

            return response()->json([
                'success' => true,
                'data' => [
                    'nombre' => $nombre,
                    'direccion' => $res->domicilioFiscal->direccion ?? ($res->domicilioFiscal->domicilio ?? ''),
                    'localidad' => $res->domicilioFiscal->localidad ?? '',
                    'provincia' => $res->domicilioFiscal->idProvincia ?? '',
                    'condicion_iva' => $this->parseCondicionIvaAfip($res->impuestos ?? [])
                ]
            ]);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            
            // Traducción amigable de errores técnicos de AFIP
            if (str_contains($msg, 'notAuthorized')) {
                $msg = "Tu CUIT no tiene autorizado el servicio 'ws_sr_padron_a5' en AFIP. Debes delegarlo/asociarlo al certificado en la web oficial.";
            }

            return response()->json(['success' => false, 'error' => $msg], 200); // 200 para que el frontend maneje el msg
        }
    }

    /**
     * 🧙‍♂️ Generar CSR y Private Key para AFIP
     */
    public function generateCertificate(Request $request)
    {
        try {
            $user = auth()->user();
            $empresa = $user->empresa;

            $request->validate([
                'cuit' => 'required',
                'razon_social' => 'required|string|max:100',
                'localidad' => 'required|string|max:100',
                'provincia' => 'required|string|max:100',
            ]);

            $cuit = str_replace('-', '', $request->cuit);
            $razonSocial = $request->razon_social;
            
            // DN para AFIP
            $dn = [
                "countryName" => "AR",
                "stateOrProvinceName" => $request->provincia,
                "localityName" => $request->localidad,
                "organizationName" => $razonSocial,
                "organizationalUnitName" => "Sistemas",
                "commonName" => $razonSocial,
                "serialNumber" => "CUIT " . $cuit
            ];

            // Generar clave privada
            $privkey = openssl_pkey_new([
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ]);

            // Generar CSR
            $csr = openssl_csr_new($dn, $privkey, array('digest_alg' => 'sha256'));

            openssl_csr_export($csr, $csrout);
            openssl_pkey_export($privkey, $pkeyout);

            // Guardar temporalmente en sesión para descarga
            $tempFolder = storage_path('app/temp_certs/' . $empresa->id);
            if (!file_exists($tempFolder)) {
                mkdir($tempFolder, 0777, true);
            }

            file_put_contents($tempFolder . '/afip.key', $pkeyout);
            file_put_contents($tempFolder . '/afip.csr', $csrout);

            return response()->json([
                'success' => true,
                'message' => '¡Archivos generados con éxito!',
                'download_key' => route('empresa.configuracion.download_cert', ['type' => 'key']),
                'download_csr' => route('empresa.configuracion.download_cert', ['type' => 'csr']),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📥 Descargar archivos generados
     */
    public function downloadCert($type)
    {
        $empresaId = auth()->user()->empresa_id;
        $path = storage_path('app/temp_certs/' . $empresaId . '/afip.' . $type);

        if (!file_exists($path)) {
            abort(404, "Archivo no encontrado. Por favor genera los certificados nuevamente.");
        }

        $filename = ($type == 'key') ? 'afip_privada.key' : 'pedido_afip.csr';

        return response()->download($path, $filename);
    }

    protected function parseCondicionIvaAfip($impuestos)
    {
        // Simplificado
        foreach ($impuestos as $imp) {
            if ($imp == 20) return 'Responsable Inscripto';
            if ($imp == 32) return 'Exento';
            if ($imp == 33) return 'Monotributista';
        }
        return 'Consumidor Final';
    }
}
