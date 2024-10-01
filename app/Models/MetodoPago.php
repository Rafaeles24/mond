<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodo_pago';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
