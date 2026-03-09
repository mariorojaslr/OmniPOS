<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToEmpresa;

class KardexMovimiento extends Model
{
    use BelongsToEmpresa;

    protected $table = 'kardex_movimientos';

    protected $fillable = [
        'empresa_id',
        'product_id',
        'user_id',
        'tipo',
        'cantidad',
        'stock_resultante',
        'origen'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
