<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Venta extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'user_id',
        'client_id',
        'tipo_comprobante',
        'numero_comprobante',
        'total_sin_iva',
        'total_iva',
        'total_con_iva',
        'metodo_pago',
        'monto_pagado',
        'vuelto',
        'cae',
        'cae_vencimiento',
        'qr_data',
        'afip_error',
    ];

    public function items()
    {
        return $this->hasMany(VentaItem::class);
    }

    /**
     * 🚚 Listado de entregas realizadas
     */
    public function remitos()
    {
        return $this->hasMany(Remito::class);
    }


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    /**
     * ⚖️ Calcula el porcentaje total de mercadería entregada
     */
    public function getPorcentajeEntregaAttribute()
    {
        $totalVendido = $this->items->sum('cantidad');
        if ($totalVendido == 0) return 100;
        
        $totalEntregado = $this->items->sum('cantidad_entregada');
        return round(($totalEntregado / $totalVendido) * 100, 2);
    }

    /**
     * 🛡️ Indica si la venta aún tiene productos en guarda
     */
    public function getEsGuardaPendienteAttribute()
    {
        return $this->items->contains(fn($item) => $item->cantidad_pendiente > 0);
    }

    /**
     * 💳 Ledger asociado (para ventas en cuenta corriente)
     */
    public function ledger()
    {
        return $this->morphOne(\App\Models\ClientLedger::class, 'reference');
    }
}
