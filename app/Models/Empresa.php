<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Empresa extends Model
{
    use HasFactory;

    /**
     * TABLA ASOCIADA
     */
    protected $table = 'empresas';

    /**
     * CAMPOS EDITABLES MASIVAMENTE
     */
    protected $fillable = [
        'nombre_comercial',
        'slug',
        'razon_social',
        'email',
        'telefono',
        'activo',
        'fecha_vencimiento',
        'fecha_cierre_ejercicio',
        'plan_id',
        'status',
        'ultima_fecha_pago',
        // Datos fiscales
        'cuit',
        'condicion_iva',
        'iibb',
        'punto_venta',
        'proximo_numero_factura',
        'direccion_fiscal',
        'dia_cierre_periodo',
        'config_pasarelas',
        // ARCA (AFIP)
        'arca_cuit',
        'arca_punto_venta',
        'arca_certificado',
        'arca_llave',
        'arca_ambiente',
    ];

    /**
     * CASTS AUTOMÁTICOS
     */
    protected $casts = [
        'activo'                 => 'boolean',
        'fecha_vencimiento'      => 'date',
        'fecha_cierre_ejercicio' => 'date',
        'ultima_fecha_pago'      => 'date',
        'config_pasarelas'       => 'array',
    ];

    // =========================================================
    // RELACIONES

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function pagos()
    {
        return $this->hasMany(SuscripcionPago::class);
    }
    public function presupuestos()
    {
        return $this->hasMany(\App\Models\Presupuesto::class);
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }

    public function clients()
    {
        return $this->hasMany(\App\Models\Client::class);
    }

    // =========================================================

    public function plan()
    {
        return $this->belongsTo(\App\Models\Plan::class);
    }

    /**
     * CONFIGURACIÓN VISUAL DE LA EMPRESA
     */
    public function config()
    {
        return $this->hasOne(\App\Models\EmpresaConfig::class, 'empresa_id');
    }

    /**
     * USUARIOS QUE PERTENECEN A ESTA EMPRESA
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'empresa_id');
    }

    // =========================================================
    // ESTADO DE LA EMPRESA
    // =========================================================

    /**
     * DEVUELVE EL ESTADO EN TEXTO
     */
    public function estadoLabel(): string
    {
        if (! $this->activo) {
            return 'Inactiva';
        }

        if ($this->fecha_vencimiento instanceof Carbon && $this->fecha_vencimiento->isPast()) {
            return 'Vencida';
        }

        return 'Activa';
    }

    /**
     * INDICA SI LA EMPRESA ESTÁ VENCIDA
     */
    public function estaVencida(): bool
    {
        return $this->fecha_vencimiento instanceof Carbon
            && $this->fecha_vencimiento->isPast();
    }

    /**
     * RENUEVA LA EMPRESA X DÍAS
     */
    public function renovar(int $dias = 30): void
    {
        $this->update([
            'fecha_vencimiento' => now()->addDays($dias),
            'activo' => true,
        ]);
    }

    /**
     * ACTIVA / DESACTIVA LA EMPRESA
     */
    public function toggleActivo(): void
    {
        $this->update([
            'activo' => ! $this->activo,
        ]);
    }

    // =========================================================
    // CONTABILIDAD
    // =========================================================

    /**
     * DEVUELVE LA FECHA DE CIERRE CONTABLE FORMATEADA
     */
    public function cierreContableLabel(): ?string
    {
        return $this->fecha_cierre_ejercicio
            ? $this->fecha_cierre_ejercicio->format('d/m/Y')
            : null;
    }

    /**
     * INDICA SI LA FECHA ACTUAL YA PASÓ EL CIERRE CONTABLE
     */
    public function cierreVencido(): bool
    {
        return $this->fecha_cierre_ejercicio instanceof Carbon
            && $this->fecha_cierre_ejercicio->isPast();
    }

    /**
     * Usa el slug en lugar del ID para las rutas
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
