<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Supplier extends Model
{
    use BelongsToEmpresa;

    protected $table = 'suppliers';

    /*
    |--------------------------------------------------------------------------
    | CAMPOS EDITABLES
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'empresa_id',
        'name',
        'phone',
        'email',
        'document',
        'condicion_iva',
        'tipo_factura_default',
        'cuit',
        'direccion',
        'saldo',
        'active'
    ];


    /*
    |--------------------------------------------------------------------------
    | RELACIÓN → CUENTA CORRIENTE (SupplierLedger)
    |--------------------------------------------------------------------------
    */
    public function ledger()
    {
        return $this->hasMany(SupplierLedger::class, 'supplier_id');
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER TIPO DE FACTURA SEGÚN IVA
    |--------------------------------------------------------------------------
    | Responsable Inscripto → A
    | Monotributo → C
    | Consumidor Final → B
    |--------------------------------------------------------------------------
    */
    public function tipoFacturaAutomatico()
    {
        switch ($this->condicion_iva) {

            case 'responsable_inscripto':
                return 'A';

            case 'monotributo':
                return 'C';

            case 'consumidor_final':
                return 'B';

            default:
                return $this->tipo_factura_default ?? 'A';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR SALDO AUTOMÁTICO (Cuenta Corriente)
    |--------------------------------------------------------------------------
    | Débito  → aumenta deuda
    | Crédito → reduce deuda
    |--------------------------------------------------------------------------
    */
    public function recalcularSaldo()
    {
        $debe = $this->ledger()
            ->where('type', 'debit')
            ->sum('amount');

        $haber = $this->ledger()
            ->where('type', 'credit')
            ->sum('amount');

        $this->saldo = $debe - $haber;
        $this->save();
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR DEUDA (Compra) - POLIMÓRFICO
    |--------------------------------------------------------------------------
    */
    public function registrarCompra(Purchase $purchase, $descripcion = 'Compra registrada')
    {
        SupplierLedger::create([
            'empresa_id'     => $this->empresa_id,
            'supplier_id'    => $this->id,
            'type'           => 'debit',
            'amount'         => $purchase->total,
            'pending_amount' => $purchase->total,
            'description'    => $descripcion,
            'reference_type' => Purchase::class,
            'reference_id'   => $purchase->id,
            'paid'           => false
        ]);

        $this->recalcularSaldo();
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR PAGO (Orden de Pago) - POLIMÓRFICO
    |--------------------------------------------------------------------------
    */
    public function registrarPago(OrdenPago $ordenPago, $descripcion = 'Pago registrado')
    {
        SupplierLedger::create([
            'empresa_id'     => $this->empresa_id,
            'supplier_id'    => $this->id,
            'type'           => 'credit',
            'amount'         => $ordenPago->monto_total,
            'pending_amount' => 0, // Los créditos no suelen tener "pendiente" de ser pagados
            'description'    => $descripcion,
            'reference_type' => OrdenPago::class,
            'reference_id'   => $ordenPago->id,
            'paid'           => true
        ]);

        $this->recalcularSaldo();
    }
}
