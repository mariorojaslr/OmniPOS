<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\KardexMovimiento;
use App\Models\Product;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'color',
        'size',
        'price',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Descontar stock de la variante
     */
    public function descontarStock($cantidad, $origen = 'VENTA')
    {
        DB::transaction(function () use ($cantidad, $origen) {
            $this->stock = max(0, $this->stock - $cantidad);
            $this->save();

            $this->product->stock = max(0, $this->product->stock - $cantidad);
            $this->product->save();

            KardexMovimiento::create([
                'empresa_id'       => $this->product->empresa_id,
                'product_id'       => $this->product_id,
                'user_id'          => auth()->id(),
                'tipo'             => 'salida',
                'cantidad'         => -$cantidad,
                'stock_resultante' => $this->stock,
                'origen'           => $origen . " (Variante: {$this->size} / {$this->color})",
            ]);
        });
    }

    /**
     * Aumentar stock de la variante
     */
    public function aumentarStock($cantidad, $origen = 'INGRESO')
    {
        DB::transaction(function () use ($cantidad, $origen) {
            $this->stock += $cantidad;
            $this->save();

            // Sincronizar stock con el producto padre
            $this->product->stock = ProductVariant::where('product_id', $this->product_id)->sum('stock');
            $this->product->save();

            KardexMovimiento::create([
                'empresa_id'       => $this->product->empresa_id,
                'product_id'       => $this->product_id,
                'user_id'          => auth()->id(),
                'tipo'             => 'entrada',
                'cantidad'         => $cantidad,
                'stock_resultante' => $this->stock,
                'origen'           => $origen . " (Variante: {$this->size} / {$this->color})",
            ]);
        });
    }
}
