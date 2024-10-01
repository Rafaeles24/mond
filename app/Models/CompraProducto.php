<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraProducto extends Model
{
    protected $table = 'compra_producto';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function producto() {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
