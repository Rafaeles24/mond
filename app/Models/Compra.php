<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';

    protected $fillable = [
        'usuario_id',
        'metodo_pago_id',
        'precio_final',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function productos() {
        return $this->hasMany(CompraProducto::class);
    }

}
