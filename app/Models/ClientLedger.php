<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ReciboImputacion;

class ClientLedger extends Model
{
    use BelongsToEmpresa;

    use HasFactory;

    protected $table = 'client_ledgers';

    protected $fillable = [
        'empresa_id',
        'client_id',
        'reference_type',
        'reference_id',
        'type',
        'amount',
        'description',
        'paid',
        'created_at',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'created_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * =====================================================
     * BLOQUEO CONTABLE - MOVIMIENTOS INMUTABLES
     * =====================================================
     */

    protected static function boot()
    {
        parent::boot();

        // BLOQUEAR EDICION (Excepto paid que es operativo)
        static::updating(function ($model) {
            if ($model->isDirty(['amount', 'type', 'client_id', 'empresa_id'])) {
                 throw new \Exception("No se permite modificar los valores base de movimientos contables. Debe generar un movimiento compensatorio.");
            }
        });

        // BLOQUEAR ELIMINACION
        static::deleting(function ($model) {
            throw new \Exception("No se permite eliminar movimientos contables. Debe generar un movimiento compensatorio.");
        });
    }

    /**
     * RELACIONES
     */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Referencia al objeto original (Venta, Recibo, etc)
     */
    public function reference()
    {
        return $this->morphTo('reference', 'reference_type', 'reference_id');
    }

    /**
     * Imputaciones recibidas (si es un DEBIT)
     */
    public function imputaciones()
    {
        return $this->hasMany(ReciboImputacion::class, 'ledger_id');
    }
}
