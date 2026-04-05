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
        $resFolder = storage_path('app/afip_res/');
        if (!file_exists($resFolder)) {
            mkdir($resFolder, 0777, true);
        }

        $certPathOnDisk = storage_path('app/ARCA/empresa_' . $empresa->id . '_cert.crt');
        $keyPathOnDisk = storage_path('app/ARCA/empresa_' . $empresa->id . '_key.key');

        if (!file_exists($certPathOnDisk) || !file_exists($keyPathOnDisk)) {
            throw new \Exception("Faltan certificados AFIP en la carpeta ARCA.");
        }

        return new Afip([
            'CUIT'         => (int) str_replace('-', '', $empresa->arca_cuit),
            'production'   => ($empresa->arca_ambiente === 'produccion'),
            'cert'         => file_get_contents($certPathOnDisk),
            'key'          => file_get_contents($keyPathOnDisk),
            'res_folder'   => $resFolder,
            'access_token' => env('AFIP_ACCESS_TOKEN'),
        ]);
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
            $tipoCompAfip = $this->getTipoComprobanteAfip($venta->tipo_comprobante, $empresa->condicion_iva);
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

            return [
                'success'           => true,
                'cae'               => $res['CAE'],
                'cae_vencimiento'   => $res['CAEVto'],
                'numero_comprobante' => str_pad($puntoVenta, 5, '0', STR_PAD_LEFT) . '-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT)
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
    protected function getTipoComprobanteAfip($tipo, $condicionIva)
    {
        if ($condicionIva === 'Monotributista') {
            return ($tipo === 'NC' || $tipo === 'nota_credito') ? 13 : 11; // 11=FC C, 13=NC C
        }

        // Simplificado para B (6) o NC B (8) - Asumimos B si no es Monotributista por ahora
        // Podríamos discriminar A si el cliente es resp. inscripto
        return ($tipo === 'NC' || $tipo === 'nota_credito') ? 8 : 6; 
    }

    protected function getDocTipo($cliente)
    {
        if (!$cliente || !$cliente->document) return 99; // Sin identificación
        $doc = str_replace('-', '', $cliente->document);
        return (strlen($doc) > 8) ? 80 : 96; // 80=CUIT, 96=DNI
    }
}
