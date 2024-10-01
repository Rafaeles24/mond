<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';

    protected $fillable = [
        'usuario_id',
        'producto_id',
        'calificacion',
        'comentario',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
