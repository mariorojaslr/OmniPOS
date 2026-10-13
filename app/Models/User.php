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
        'consultorio',
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

    public function esEnfermero(): bool
    {
        if ($this->sub_role === 'enfermero') {
            return true;
        }
        $config = $this->profesionalConfig;
        if ($config && is_array($config->especialidades)) {
            foreach ($config->especialidades as $esp) {
                // Si la especialidad cargada contiene la palabra "enfermeria"
                if (stripos((string)$esp, 'enfermer') !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | ESTADO
    |--------------------------------------------------------------------------
    */

    public function estaActivo(): bool
    {
        return (bool)$this->activo && (empty($this->status) || $this->status === 'activo');
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

    public function getEmpresaAttribute()
    {
        if ($this->relationLoaded('empresa')) {
            $rel = $this->getRelationValue('empresa');
            if ($rel) return $rel;
        }

        if ($this->empresa_id) {
            $rel = $this->getRelationValue('empresa');
            if ($rel) return $rel;
        }

        if ($this->role === 'owner') {
            $empresa = \App\Models\Empresa::where('slug', 'clinica-dental-prueba')->first()
                    ?? \App\Models\Empresa::where('tipo_clinica', 'clinica_odontologica')->first()
                    ?? \App\Models\Empresa::first();
            if ($empresa) {
                $this->setRelation('empresa', $empresa);
                return $empresa;
            }
        }

        return null;
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

    public function empresasAsignadas()
    {
        return $this->hasMany(Empresa::class, 'reseller_id');
    }
    public function resellerConfig()
    {
        return $this->hasOne(ResellerConfig::class, 'user_id');
    }
}
