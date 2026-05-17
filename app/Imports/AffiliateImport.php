<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class AffiliateImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Client([
            'empresa_id'       => Auth::user()->empresa_id,
            'name'             => $row['nombre_completo'],
            'document'         => $row['documento'],
            'email'            => $row['email'] ?? null,
            'phone'            => $row['telefono'] ?? null,
            'workplace'        => $row['lugar_trabajo'] ?? null,
            'affiliate_number' => $row['nro_afiliado_os'] ?? null,
            'monthly_fee'      => $row['cuota_mensual'] ?? 0,
            'is_affiliate'     => true,
            'affiliate_status' => 'active',
            'affiliate_since'  => now(),
        ]);
    }
}
