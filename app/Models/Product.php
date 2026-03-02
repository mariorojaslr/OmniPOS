<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN BÁSICA
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';

    protected $fillable = [
        'empresa_id',
        'name',
        'description_short',
        'description_long',
        'price',
        'stock',        // STOCK ACTUAL REAL
        'stock_min',    // STOCK MÍNIMO (alerta)
        'stock_ideal',  // STOCK IDEAL
        'active'
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'float',
        'stock_min' => 'float',
        'stock_ideal' => 'float',
        'active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPE GLOBAL MULTIEMPRESA (AUTOMÁTICO)
    |--------------------------------------------------------------------------
    | Todo Product se filtra por empresa logueada
    */

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('empresa_id', auth()->user()->empresa_id);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Empresa propietaria
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Imágenes del producto
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Video del producto
    public function video()
    {
        return $this->hasOne(ProductVideo::class);
    }

    // Items vendidos (estadísticas / ranking)
    public function ventaItems()
    {
        return $this->hasMany(VentaItem::class, 'product_id');
    }

    // Movimientos Kardex (HISTORIAL DE STOCK)
    public function movimientos()
    {
        return $this->hasMany(KardexMovimiento::class, 'product_id');
    }

    /*
    |--------------------------------------------------------------------------
    | STOCK — MÉTODOS PROFESIONALES
    |--------------------------------------------------------------------------
    */

    /**
     * Descuenta stock y registra movimiento Kardex
     */
    public function descontarStock($cantidad, $origen = 'VENTA')
    {
        $this->stock = max(0, $this->stock - $cantidad);
        $this->save();

        KardexMovimiento::create([
            'empresa_id' => $this->empresa_id,
            'product_id' => $this->id,
            'user_id' => auth()->id(),
            'tipo' => 'salida',
            'cantidad' => $cantidad,
            'stock_resultante' => $this->stock,
            'origen' => $origen,
        ]);
    }

    /**
     * Aumenta stock y registra movimiento Kardex
     */
    public function aumentarStock($cantidad, $origen = 'INGRESO')
    {
        $this->stock += $cantidad;
        $this->save();

        KardexMovimiento::create([
            'empresa_id' => $this->empresa_id,
            'product_id' => $this->id,
            'user_id' => auth()->id(),
            'tipo' => 'entrada',
            'cantidad' => $cantidad,
            'stock_resultante' => $this->stock,
            'origen' => $origen,
        ]);
    }

    /**
     * Ajuste manual de stock (corrige diferencias)
     */
    public function ajustarStock($nuevoStock, $origen = 'AJUSTE')
    {
        $diferencia = $nuevoStock - $this->stock;
        $this->stock = $nuevoStock;
        $this->save();

        KardexMovimiento::create([
            'empresa_id' => $this->empresa_id,
            'product_id' => $this->id,
            'user_id' => auth()->id(),
            'tipo' => 'ajuste',
            'cantidad' => $diferencia,
            'stock_resultante' => $this->stock,
            'origen' => $origen,
        ]);
    }

    /**
     * Verifica si el stock está bajo
     */
    public function stockBajo()
    {
        return $this->stock <= $this->stock_min;
    }

    /*
    |--------------------------------------------------------------------------
    | ATRIBUTOS CALCULADOS
    |--------------------------------------------------------------------------
    */

    /**
     * Estado del stock (critico / bajo / ok)
     */
    public function getEstadoStockAttribute()
    {
        if ($this->stock <= 0) {
            return 'critico';
        }

        if ($this->stock <= $this->stock_min) {
            return 'bajo';
        }

        return 'ok';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES ÚTILES
    |--------------------------------------------------------------------------
    */

    // Solo productos activos
    public function scopeActivos($query)
    {
        return $query->where('active', true);
    }

    // Solo productos con stock disponible
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
