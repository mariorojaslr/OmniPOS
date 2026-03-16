<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\BelongsToEmpresa;

class Product extends Model
{
    use BelongsToEmpresa;

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
        'active',
        'has_variants',
        'is_combo'
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    | Conversiones automáticas de tipos
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'price'        => 'float',
        'stock'        => 'float',
        'stock_min'    => 'float',
        'stock_ideal'  => 'float',
        'active'       => 'boolean',
        'has_variants' => 'boolean',
        'is_combo'     => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Variantes (Talles / Colores)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Items del combo (Si este producto es un combo)
    public function comboItems()
    {
        return $this->hasMany(ProductCombo::class, 'parent_product_id');
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
            
            // Si es un combo, descontamos de los hijos
            if ($this->is_combo) {
                foreach ($this->comboItems as $item) {
                    $childProduct = $item->child;
                    if ($childProduct) {
                        $childProduct->descontarStock($item->quantity * $cantidad, $origen . " (Combo: {$this->name})");
                    }
                }
            }

            $this->stock = max(0, $this->stock - $cantidad);
            $this->save();

            KardexMovimiento::create([
                'empresa_id'       => $this->empresa_id,
                'product_id'       => $this->id,
                'user_id'          => auth()->id(),
                'tipo'             => 'salida',
                'cantidad'         => -$cantidad,
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
