<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN BÁSICA
    |--------------------------------------------------------------------------
    | Definición de la tabla y campos permitidos
    | El campo oficial de inventario es: stock
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';

    protected $fillable = [
        'empresa_id',
        'name',

        // Contenido comercial
        'descripcion_corta',
        'descripcion_larga',

        // Precio final
        'price',

        // Inventario
        'stock',
        'stock_min',
        'stock_ideal',

        // Estado
        'active'
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    | Conversiones automáticas de tipos
    |--------------------------------------------------------------------------
    */

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
    | Todos los productos quedan filtrados automáticamente
    | por la empresa del usuario autenticado.
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


    // Imágenes del producto
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    // Videos del producto
    public function videos()
    {
        return $this->hasMany(ProductVideo::class)
            ->latest();
    }


    // Items de ventas (ranking / estadísticas)
    public function ventaItems()
    {
        return $this->hasMany(VentaItem::class, 'product_id');
    }


    // Kardex de movimientos
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
     * Determina si puede agregar más videos
     * límite recomendado: 3
     */
    public function puedeAgregarVideo(): bool
    {
        return $this->videos()->count() < 3;
    }


    /**
     * Indica si el producto tiene videos
     */
    public function tieneVideos(): bool
    {
        return $this->videos()->exists();
    }



    /*
    |--------------------------------------------------------------------------
    | MOTOR DE INVENTARIO
    |--------------------------------------------------------------------------
    | Todas las operaciones de stock pasan por aquí.
    | Esto garantiza consistencia con Kardex.
    |--------------------------------------------------------------------------
    */


    /**
     * Descontar stock (ventas / POS)
     */
    public function descontarStock($cantidad, $origen = 'VENTA')
    {
        DB::transaction(function () use ($cantidad, $origen) {
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
        });
    }



    /**
     * Aumentar stock (compras / ingresos)
     */
    public function aumentarStock($cantidad, $origen = 'INGRESO')
    {
        DB::transaction(function () use ($cantidad, $origen) {
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
        });
    }



    /**
     * Ajuste manual de stock
     */
    public function ajustarStock($nuevoStock, $origen = 'AJUSTE')
    {
        DB::transaction(function () use ($nuevoStock, $origen) {
            $diferencia = $nuevoStock - $this->stock;
            $this->stock = $nuevoStock;
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
        });
    }



    /*
    |--------------------------------------------------------------------------
    | REGLAS DE INVENTARIO
    |--------------------------------------------------------------------------
    */


    /**
     * Detecta si el stock está bajo
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
     * Estado del stock
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
    | SCOPES
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
