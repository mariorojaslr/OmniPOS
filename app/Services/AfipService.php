<?php

namespace App\Services;

use App\Models\Empresa;
use Afip;
use Illuminate\Support\Facades\Log;

class AfipService
{
    /**
     * Obtener instancia del SDK de AFIP configurada para la empresa
     */
    protected function getAfipInstance(Empresa $empresa)
    {
        // Si tenemos un Token de Acceso (Cloud SDK), lo usamos como prioridad
        $accessToken = env('AFIP_ACCESS_TOKEN'); // Leemos directo del .env
        $isProduction = (strpos(strtolower($empresa->arca_ambiente), 'prod') !== false);

        // Definimos las rutas posibles
        $paths = [
            'specific' => base_path("ARCA/empresa_{$empresa->id}_cert.crt"),
            'specific_key' => base_path("ARCA/empresa_{$empresa->id}_key.key"),
            'generic' => base_path('ARCA/empresa.crt'),
            'generic_key' => base_path('ARCA/empresa.key'),
            'special' => base_path('ARCA/empresa .key'),
        ];

        // LOG DE DIAGNÓSTICO (Solo visible en logs del sistema)
        Log::info("DIAGNÓSTICO AFIP - Empresa: " . $empresa->id);
        Log::info("¿Existe Specific Cert?: " . (file_exists($paths['specific']) ? 'SÍ' : 'NO'));
        Log::info("¿Existe Specific Key?: " . (file_exists($paths['specific_key']) ? 'SÍ' : 'NO'));
        Log::info("¿Hay Token en .env?: " . ($accessToken ? 'SÍ ('.substr($accessToken, 0, 5).'...)' : 'NO'));

        // Resolución de Certificado
        $certContent = null;
        if (file_exists($paths['specific'])) $certContent = file_get_contents($paths['specific']);
        elseif (file_exists($paths['generic'])) $certContent = file_get_contents($paths['generic']);

        // Resolución de Llave
        $keyContent = null;
        if (file_exists($paths['specific_key'])) $keyContent = file_get_contents($paths['specific_key']);
        elseif (file_exists($paths['generic_key'])) $keyContent = file_get_contents($paths['generic_key']);
        elseif (file_exists($paths['special'])) $keyContent = file_get_contents($paths['special']);

        if ($certContent && $keyContent) {
            return new \Afip([
                'CUIT'         => (float) str_replace('-', '', $empresa->arca_cuit),
                'production'   => $isProduction,
                'cert'         => $certContent,
                'key'          => $keyContent,
                'ta_folder'    => storage_path('app/ARCA/ta/' . $empresa->id),
            ]);
        }

        if ($accessToken) {
            return new \Afip([
                'CUIT'          => (float) str_replace('-', '', $empresa->arca_cuit),
                'production'    => $isProduction,
                'access_token'  => $accessToken,
            ]);
        }

        throw new \Exception("BLOQUEO: No se encontró el certificado en " . $paths['specific'] . " ni el Token en el .env. Subí los archivos al servidor o cargá el Token.");
    }

    /**
     * Solicitar CAE para una factura
     */
    public function solicitarCAE($empresa, $venta)
    {
        try {
            $afip = $this->getAfipInstance($empresa);
            
            // Determinar Tipo de Comprobante según AFIP Codes
            // 1=A, 6=B, 11=C, 3=NC A, 8=NC B, 13=NC C
            $tipoCompAfip = $this->getTipoComprobanteAfip($venta->tipo_comprobante, $empresa->condicion_iva, $venta->cliente);
            $puntoVenta = $empresa->arca_punto_venta ?? ($empresa->punto_venta ?? 1);

            $lastVoucher = $afip->ElectronicBilling->GetLastVoucher($puntoVenta, $tipoCompAfip);
            $nextNumber = $lastVoucher + 1;

            // Datos para AFIP
            $data = [
                'CantReg'      => 1,
                'PtoVta'       => $puntoVenta,
                'CbteTipo'     => $tipoCompAfip,
                'Concepto'     => 1, // Productos
                'DocTipo'      => $this->getDocTipo($venta->cliente),
                'DocNro'       => (int) str_replace('-', '', $venta->cliente->document ?? 0),
                'CbteDesde'    => $nextNumber,
                'CbteHasta'    => $nextNumber,
                'CbteFch'      => date('Ymd'),
                'ImpTotal'     => round($venta->total_con_iva, 2),
                'ImpTotConc'   => 0,
                'ImpNeto'      => round($venta->total_sin_iva, 2),
                'ImpOpEx'      => 0,
                'ImpIVA'       => round($venta->total_iva, 2),
                'ImpTrib'      => 0,
                'MonId'        => 'PES',
                'MonCotiz'     => 1,
            ];

            // Si es Responsable Inscripto (A o B) hay que desglosar el IVA
            if ($empresa->condicion_iva !== 'Monotributista') {
                $data['Iva'] = [
                    [
                        'Id'     => 5, // 21%
                        'BaseImp' => round($venta->total_sin_iva, 2),
                        'Importe' => round($venta->total_iva, 2)
                    ]
                ];
            }

            $res = $afip->ElectronicBilling->CreateVoucher($data);

            // 📱 GENERAR QR DATA (Requerido por AFIP desde 2021)
            $qrData = [
                "ver" => 1,
                "fecha" => date('Y-m-d', strtotime($data['CbteFch'])),
                "cuit" => (int) str_replace('-', '', $empresa->arca_cuit),
                "ptoVta" => (int) $puntoVenta,
                "tipoCbte" => (int) $tipoCompAfip,
                "nroCbte" => (int) $nextNumber,
                "importe" => (float) $data['ImpTotal'],
                "moneda" => "PES",
                "ctz" => 1,
                "tipoDocRec" => (int) $data['DocTipo'],
                "nroDocRec" => (int) $data['DocNro'],
                "tipoCodAut" => "E",
                "codAut" => (int) $res['CAE']
            ];

            return [
                'success'           => true,
                'cae'               => $res['CAE'],
                'cae_vencimiento'   => $res['CAEVto'],
                'numero_comprobante' => str_pad($puntoVenta, 5, '0', STR_PAD_LEFT) . '-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT),
                'qr_data'           => base64_encode(json_encode($qrData))
            ];

        } catch (\Exception $e) {
            Log::error("Error AFIP Solicitar CAE: " . $e->getMessage());
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }

    /**
     * Mapeo de tipos de comprobante AFIP
     */
    protected function getTipoComprobanteAfip($tipo, $condicionIva, $cliente = null)
    {
        if ($condicionIva === 'Monotributista') {
            return ($tipo === 'NC' || $tipo === 'nota_credito') ? 13 : 11; // 11=FC C, 13=NC C
        }

        // Si el cliente es Responsable Inscripto -> Comprobante A (1 o 3)
        $clienteEsRI = ($cliente && $cliente->tax_condition === 'responsable_inscripto');

        // ⚖️ DISCRIMINACIÓN A o B (RI -> RI = A | RI -> Otros = B)
        return ($tipo === 'NC' || $tipo === 'nota_credito') 
            ? ($clienteEsRI ? 3 : 8)  // 3=NC A, 8=NC B
            : ($clienteEsRI ? 1 : 6); // 1=FC A, 6=FC B
    }

    protected function getDocTipo($cliente)
    {
        if (!$cliente || !$cliente->document) return 99; // Sin identificación
        $doc = str_replace('-', '', $cliente->document);
        // Si es CUIT (11 dígitos) debe ser 80
        return (strlen($doc) >= 10) ? 80 : 96; // 80=CUIT, 96=DNI
    }

    /**
     * Consultar datos de una persona por CUIT (Padron AFIP)
     */
    public function getDatosPersona(Empresa $empresa, $cuit)
    {
        try {
            $afip = $this->getAfipInstance($empresa);
            $taxpayer_details = $afip->RegisterCMS->GetTaxpayerDetails($cuit);

            if (!$taxpayer_details) {
                return ['success' => false, 'error' => 'No se encontraron datos para ese CUIT.'];
            }

            return [
                'success'       => true,
                'nombre'        => $taxpayer_details->datosGenerales->nombre ?? ($taxpayer_details->datosGenerales->razonSocial ?? ''),
                'direccion'     => $taxpayer_details->datosGenerales->domicilioFiscal->direccion ?? '',
                'localidad'     => $taxpayer_details->datosGenerales->domicilioFiscal->localidad ?? '',
                'id_persona'    => $taxpayer_details->datosGenerales->idPersona,
                'tipo_persona'  => $taxpayer_details->datosGenerales->tipoPersona,
                'estado'        => $taxpayer_details->datosGenerales->estadoClave,
                'detalles'      => $taxpayer_details
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
