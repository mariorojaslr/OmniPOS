<?php

namespace App\Exports;

use App\Models\KardexMovimiento;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KardexExport implements FromCollection, WithHeadings
{
    protected $productId;
    protected $desde;
    protected $hasta;

    public function __construct($productId, $desde = null, $hasta = null)
    {
        $this->productId = $productId;
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function collection()
    {
        $query = KardexMovimiento::where('empresa_id', \Illuminate\Support\Facades\Auth::user()->empresa_id)
            ->where('product_id', $this->productId);

        if ($this->desde) {
            $query->whereDate('created_at', '>=', $this->desde);
        }
        if ($this->hasta) {
            $query->whereDate('created_at', '<=', $this->hasta);
        }

        return $query->orderBy('created_at')
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
