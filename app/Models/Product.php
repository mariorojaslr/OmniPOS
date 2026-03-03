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

        // Descripciones marketing
        'descripcion_corta',
        'descripcion_larga',

        'price',

        // Stock
        'stock',
        'stock_min',
        'stock_ideal',

        'active'
    ];

    protected $casts = [
        'price'       => 'float',
        'stock'       => 'float',
        'stock_min'   => 'float',
        'stock_ideal' => 'float',
        'active'      => 'boolean',
    ];



    /*
    |--------------------------------------------------------------------------
    | GLOBAL SCOPE MULTIEMPRESA
    |--------------------------------------------------------------------------
    | Todos los productos quedan automáticamente filtrados
    | por la empresa logueada.
    |--------------------------------------------------------------------------
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

    // Imágenes (máximo 5 permitido en controlador)
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Videos (máximo 3 recomendado)
    public function videos()
    {
        return $this->hasMany(ProductVideo::class)
                    ->latest();
    }

    // Items vendidos (estadísticas / ranking)
    public function ventaItems()
    {
        return $this->hasMany(VentaItem::class, 'product_id');
    }

    // Movimientos Kardex
    public function movimientos()
    {
        return $this->hasMany(KardexMovimiento::class, 'product_id');
    }



    /*
    |--------------------------------------------------------------------------
    | VIDEO — LÓGICA DE NEGOCIO
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica si puede agregar más videos.
     * Límite profesional: 3 por producto.
     */
    public function puedeAgregarVideo(): bool
    {
        return $this->videos()->count() < 3;
    }

    /**
     * Retorna si tiene videos.
     */
    public function tieneVideos(): bool
    {
        return $this->videos()->exists();
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
            'empresa_id'       => $this->empresa_id,
            'product_id'       => $this->id,
            'user_id'          => auth()->id(),
            'tipo'             => 'salida',
            'cantidad'         => $cantidad,
            'stock_resultante' => $this->stock,
            'origen'           => $origen,
        ]);
    }

    /**
     * Aumenta stock y registra movimiento
     */
    public function aumentarStock($cantidad, $origen = 'INGRESO')
    {
        $this->stock += $cantidad;
        $this->save();

        KardexMovimiento::create([
            'empresa_id'       => $this->empresa_id,
            'product_id'       => $this->id,
            'user_id'          => auth()->id(),
            'tipo'             => 'entrada',
            'cantidad'         => $cantidad,
            'stock_resultante' => $this->stock,
            'origen'           => $origen,
        ]);
    }

    /**
     * Ajuste manual
     */
    public function ajustarStock($nuevoStock, $origen = 'AJUSTE')
    {
        $diferencia   = $nuevoStock - $this->stock;
        $this->stock  = $nuevoStock;
        $this->save();

        KardexMovimiento::create([
            'empresa_id'       => $this->empresa_id,
            'product_id'       => $this->id,
            'user_id'          => auth()->id(),
            'tipo'             => 'ajuste',
            'cantidad'         => $diferencia,
            'stock_resultante' => $this->stock,
            'origen'           => $origen,
        ]);
    }

    /**
     * ¿Stock bajo?
     */
    public function stockBajo(): bool
    {
        return $this->stock <= $this->stock_min;
    }



    /*
    |--------------------------------------------------------------------------
    | ATRIBUTOS CALCULADOS
    |--------------------------------------------------------------------------
    */

    /**
     * Estado de stock
     */
    public function getEstadoStockAttribute(): string
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

    public function scopeActivos($query)
    {
        return $query->where('active', true);
    }

    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
