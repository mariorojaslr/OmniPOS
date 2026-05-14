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
        'sub_role',
        'can_register_expenses',
        'can_manage_purchases',
        'can_sell',
        'status',
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
        'email_verified_at'     => 'datetime',
        'activo'                => 'boolean',
        'must_change_password'  => 'boolean',
        'can_register_expenses' => 'boolean',
        'can_manage_purchases'  => 'boolean',
        'can_sell'              => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */
    public const ROLE_OWNER = 'owner';
    public const ROLE_REVENDEDOR = 'revendedor';
    public const ROLE_EMPRESA = 'empresa';
    public const ROLE_USUARIO = 'usuario';


    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isRevendedor(): bool
    {
        return $this->role === self::ROLE_REVENDEDOR;
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
    | READ NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    public function readOwnerNotifications()
    {
        return $this->belongsToMany(OwnerNotification::class, 'owner_notification_reads');
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

    public function profesionalConfig()
    {
        return $this->hasOne(ProfesionalConfig::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function turnosProfesional()
    {
        return $this->hasMany(Turno::class, 'user_id');
    }
}
