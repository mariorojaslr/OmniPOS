<?php

namespace App\Exports;

use App\Models\KardexMovimiento;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KardexExport implements FromCollection, WithHeadings
{
    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function collection()
    {
        return KardexMovimiento::where('empresa_id', Auth::user()->empresa_id)
            ->where('product_id', $this->productId)
            ->orderBy('created_at')
            ->get([
                'created_at',
                'tipo',
                'cantidad',
                'stock_resultante',
                'origen'
            ]);
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Tipo',
            'Cantidad',
            'Stock Resultante',
            'Origen'
        ];
    }
}
