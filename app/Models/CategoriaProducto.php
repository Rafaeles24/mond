<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    protected $table = 'categoria_producto';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function productos() {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}
