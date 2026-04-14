<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class SupplierLedger extends Model
{
    use BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'supplier_id',
        'reference_type',
        'reference_id',
        'type',
        'amount',
        'pending_amount',
        'description',
        'paid',
        'created_at',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'created_at' => 'datetime',
        'amount' => 'decimal:2',
        'pending_amount' => 'decimal:2',
    ];

    /**
     * =====================================================
     * BLOQUEO CONTABLE - MOVIMIENTOS INMUTABLES
     * =====================================================
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear cualquier movimiento, inicializamos el pending_amount al total si no viene seteado
        static::creating(function ($model) {
            if ($model->pending_amount === null || $model->pending_amount === '') {
                $model->pending_amount = $model->amount;
            }
        });

        // BLOQUEAR EDICION (Excepto pending_amount y paid que son operativos)
        static::updating(function ($model) {
            if ($model->isDirty(['amount', 'type', 'supplier_id', 'empresa_id'])) {
                 throw new \Exception("No se permite modificar los valores base de movimientos contables de proveedores. Debe generar un movimiento compensatorio.");
            }
        });

        // BLOQUEAR ELIMINACION
        static::deleting(function ($model) {
            throw new \Exception("No se permite eliminar movimientos contables de proveedores. Debe generar un movimiento compensatorio.");
        });
    }

    /**
     * RELACIONES
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Referencia al objeto original (Purchase, OrdenPago, etc)
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
        return $this->hasMany(OrdenPagoImputacion::class, 'ledger_id');
    }
}
