<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoCarrito extends Model
{
    protected $table = 'producto_carrito';
    protected $fillable = [
        'producto_id',
        'carrito_id',
        'cantidad',
        'precio'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function producto() {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
