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
        'provincia',
        'activo',
        'fecha_vencimiento',
        'fecha_cierre_ejercicio',
        'fecha_cierre_ejercicio',
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
        'arca_activo',
    ];

    /**
     * CASTS AUTOMÁTICOS
     */
    protected $casts = [
        'activo'                 => 'boolean',
        'fecha_vencimiento'      => 'date',
        'fecha_cierre_ejercicio' => 'date',
        'ultima_fecha_pago'      => 'date',
        'ultima_fecha_pago'      => 'date',
        'config_pasarelas'       => 'array',
        'arca_activo'            => 'boolean',
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

    public function ordenesPago()
    {
        return $this->hasMany(OrdenPago::class);
    }

    public function cheques()
    {
        return $this->hasMany(Cheque::class);
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

    public function ventas()
    {
        return $this->hasMany(\App\Models\Venta::class);
    }

    public function suppliers()
    {
        return $this->hasMany(\App\Models\Supplier::class);
    }

    public function configuracion()
    {
        return $this->hasOne(\App\Models\EmpresaConfig::class, 'empresa_id');
    }

    public function config()
    {
        return $this->hasOne(\App\Models\EmpresaConfig::class, 'empresa_id');
    }

    public function productImages()
    {
        return $this->hasManyThrough(
            \App\Models\ProductImage::class,
            \App\Models\Product::class,
            'empresa_id', // Foreign key on products table...
            'product_id', // Foreign key on product_images table...
            'id', // Local key on empresas table...
            'id' // Local key on products table...
        );
    }

    // =========================================================

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
     * INDICA SI LA EMPRESA ESTÁ TOTALMENTE VENCIDA (+24 Hs de gracia)
     */
    public function estaVencidaTotalmente(): bool
    {
        if (!$this->fecha_vencimiento instanceof Carbon) return false;
        return now()->isAfter($this->fecha_vencimiento->copy()->addHours(24));
    }

    /**
     * INDICA SI ESTÁ EN EL PERÍODO DE GRACIA (0 a 24hs)
     */
    public function estaEnPeriodoDeGracia(): bool
    {
        if (!$this->fecha_vencimiento instanceof Carbon) return false;
        return now()->isAfter($this->fecha_vencimiento) && !now()->isAfter($this->fecha_vencimiento->copy()->addHours(24));
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
