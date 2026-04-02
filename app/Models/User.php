<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'empresa_id',
        'activo',
        'email_verified_at',
        'must_change_password', 
        'sub_role', // cajero, empleado
        'can_register_expenses',
        'status', // prospecto, pendiente_pago, activo, suspendido
        'lead_source',
        'country',
        'crm_notes',
        'payment_voucher',
    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'email_verified_at'    => 'datetime',
        'activo'               => 'boolean',
        'must_change_password' => 'boolean', // 👈 NUEVO
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isEmpresa(): bool
    {
        return $this->role === 'empresa';
    }

    public function isUsuario(): bool
    {
        return $this->role === 'usuario';
    }

    public function trabajaEnEmpresa(): bool
    {
        return in_array($this->role, ['empresa', 'usuario']);
    }

    public function esCajero(): bool
    {
        return $this->sub_role === 'cajero';
    }

    public function esEmpleado(): bool
    {
        return $this->sub_role === 'empleado';
    }

    /*
    |--------------------------------------------------------------------------
    | ESTADO
    |--------------------------------------------------------------------------
    */

    public function estaActivo(): bool
    {
        return $this->activo === true && $this->status === 'activo';
    }

    public function esProspecto(): bool
    {
        return $this->status === 'prospecto';
    }

    public function pendientePago(): bool
    {
        return $this->status === 'pendiente_pago';
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
