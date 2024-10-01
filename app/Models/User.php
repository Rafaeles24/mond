<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rol_id',
        'first_name',
        'last_name',
        'nickname',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'rol_id',
        'password',
        'remember_token',
        'email_verified_at',
        'estado_registro',
        'created_at',
        'updated_at',
        'avatar'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'url_avatar'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getUrlAvatarAttribute() {
        if ($this->avatar) {
            return asset('storage/avatar/' . $this->id . '/' .  $this->avatar);
        } else {
            return asset('avatar-default.svg');
        }
    }

    public function rol() {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function carrito() {
        return $this->hasOne(Carrito::class, 'usuario_id');
    }

    public function compras() {
        return $this->hasMany(Compra::class, 'usuario_id');
    }

}
