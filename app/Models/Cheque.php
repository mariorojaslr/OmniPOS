<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Cheque extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'chequera_id',
        'numero',
        'banco',
        'emisor',
        'monto',
        'fecha_emision',
        'fecha_pago',
        'estado',
        'tipo',
        'client_id',
        'supplier_id',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_emision' => 'date',
        'fecha_pago' => 'date',
    ];

    public function chequera()
    {
        return $this->belongsTo(Chequera::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
