<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'producto_img'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'producto_img',
        'categoria_id'
    ];

    protected $appends = [
        'url_imagen'
    ];

    public function getUrlImagenAttribute() {
        if ($this->producto_img) {
            return asset('storage/producto/' . $this->id . '/' .  $this->producto_img);
        } else {
            return asset('product-not-found.jpg');
        }
    }

    public function comentarios() {
        return $this->hasMany(Review::class);
    }

}