<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class Client extends Model
{
    use BelongsToEmpresa;
    /**
     * CAMPOS EDITABLES MASIVAMENTE
     */
    protected $fillable = [
        'empresa_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'document',
        'tax_condition',
        'type',
        'discount_percentage',
        'credit_limit',
        'lat',
        'lng',
        'plus_code',
        'active',
        // Plan Med Plus (Afiliados)
        'is_affiliate',
        'affiliate_number',
        'affiliate_since',
        'affiliate_status',
        'monthly_fee',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    |*/
    protected $casts = [
        'is_affiliate'    => 'boolean',
        'affiliate_since' => 'date',
        'active'          => 'boolean',
    ];

    // =========================================================
    // RELACIONES
    // =========================================================

    /**
     * MOVIMIENTOS DE CUENTA CORRIENTE
     */
    public function ledgers()
    {
        return $this->hasMany(ClientLedger::class, 'client_id');
    }

    /**
     * Alias más cómodo para cuenta corriente
     */
    public function ledger()
    {
        return $this->ledgers();
    }

    /**
     * PAGOS REGISTRADOS
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * RECIBOS (FORMALES) REGISTRADOS
     */
    public function recibos()
    {
        return $this->hasMany(Recibo::class);
    }

    /**
     * TOKEN DE ACCESO AL PORTAL CLIENTE
     */
    public function portalToken()
    {
        return $this->hasOne(ClientPortalToken::class);
    }

    /**
     * CARRITOS ASOCIADOS (POS / WEB)
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * VENTAS REALIZADAS
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * HISTORIAS CLÍNICAS (Paciente)
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'client_id');
    }

    public function bankAccounts()
    {
        return $this->morphMany(BankAccount::class, 'holder');
    }


    // =========================================================
    // MÉTODOS FINANCIEROS
    // =========================================================

    /**
     * SALDO ACTUAL (DEUDA REAL)
     * = Débitos - Créditos
     */
    public function saldo()
    {
        $debitos  = $this->ledgers()->where('type', 'debit')->sum('amount');
        $creditos = $this->ledgers()->where('type', 'credit')->sum('amount');

        return $debitos - $creditos;
    }

    /**
     * TOTAL DEUDA (solo débitos)
     */
    public function totalDeuda()
    {
        return $this->ledgers()->where('type', 'debit')->sum('amount');
    }

    /**
     * TOTAL PAGADO
     */
    public function totalPagado()
    {
        return $this->ledgers()->where('type', 'credit')->sum('amount');
    }

    /**
     * SALDO PENDIENTE (solo deudas NO pagadas)
     */
    public function saldoPendiente()
    {
        $deuda = $this->ledgers()
            ->where('type', 'debit')
            ->where('paid', 0)
            ->sum('amount');

        $pagos = $this->ledgers()
            ->where('type', 'credit')
            ->sum('amount');

        return $deuda - $pagos;
    }

    /**
     * INDICA SI EL CLIENTE ESTÁ EN DEUDA
     */
    public function tieneDeuda()
    {
        return $this->saldo() > 0;
    }
}
