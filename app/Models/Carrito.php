<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carrito';

    protected $fillable = [
        'usuario_id',
        'precio_total'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function productos() {
        return $this->hasMany(ProductoCarrito::class);
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
