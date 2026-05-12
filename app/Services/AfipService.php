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
        $isProduction = ($empresa->arca_ambiente === 'produccion' || strpos(strtolower($empresa->arca_ambiente), 'prod') !== false);
        $cuit = str_replace('-', '', $empresa->arca_cuit);

        // Carpeta para recursos de AFIP (obligatoria para el SDK)
        $resFolder = storage_path('app/afip_res/');
        if (!file_exists($resFolder)) {
            mkdir($resFolder, 0777, true);
        }

        // Rutas a los certificados en la raíz /ARCA
        $certPath = base_path("ARCA/empresa_{$empresa->id}_cert.crt");
        $keyPath  = base_path("ARCA/empresa_{$empresa->id}_key.key");

        // Prioridad 1: Certificados específicos de la empresa
        if (file_exists($certPath) && file_exists($keyPath)) {
            $certContent = file_get_contents($certPath);
            $keyContent  = file_get_contents($keyPath);

            if ($certContent === false || $keyContent === false) {
                throw new \Exception("ERROR: No se pudo leer el contenido de los certificados en $certPath. Verifica permisos.");
            }

            return new \Afip([
                'CUIT'         => $cuit,
                'production'   => $isProduction,
                'cert'         => $certContent,
                'key'          => $keyContent,
                'res_folder'   => $resFolder,
            ]);
        }

        // Prioridad 2: Certificados genéricos
        $certPathGen = base_path('ARCA/empresa.crt');
        $keyPathGen  = base_path('ARCA/empresa.key');
        if (file_exists($certPathGen) && file_exists($keyPathGen)) {
            return new \Afip([
                'CUIT'         => $cuit,
                'production'   => $isProduction,
                'cert'         => file_get_contents($certPathGen),
                'key'          => file_get_contents($keyPathGen),
                'res_folder'   => $resFolder,
            ]);
        }

        // Prioridad 3: Access Token (solo si no hay certificados)
        $accessToken = env('AFIP_ACCESS_TOKEN');
        if ($accessToken) {
            return new \Afip([
                'CUIT'          => $cuit,
                'production'    => $isProduction,
                'access_token'  => $accessToken,
            ]);
        }

        throw new \Exception("ERROR CRÍTICO: No se encontraron certificados (.crt y .key) para la empresa {$empresa->id} en la carpeta /ARCA. Verificadas rutas: $certPath y $keyPath");
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
            $debugData = [
                'error' => $e->getMessage(),
                'pv' => $puntoVenta ?? '?',
                'tipo' => $tipoCompAfip ?? '?',
                'cuit_emp' => $empresa->arca_cuit,
                'doc_cliente' => $venta->cliente->document ?? '0'
            ];
            
            return [
                'success' => false,
                'error'   => "DETALLE TÉCNICO AFIP: " . json_encode($debugData)
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
