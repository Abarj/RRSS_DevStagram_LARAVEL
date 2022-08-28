<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id'
    ];

    // Relaciones

    public function user() {

        // belongsTo | UN post pertenece a UN usuario
        // Por default trae todos los datos ->select para seleccionar solo los datos que queremos
        return $this->belongsTo(User::class)->select(['name', 'username']);
    }

    public function comentarios() {
        
        // hasMany | Un post tiene MUCHOS comentarios
        return $this->hasMany(Comentario::class);
    }

    public function likes() {

        // hasMany | Un post tiene MUCHOS likes
        return $this->hasMany(Like::class);
    }

    // --------------

    public function checkLike(User $user) {
        
        // Revisa que en la tabla de likes ya exista un registro con el User_id del usuario que da like para evitar duplicados
        return $this->likes->contains('user_id', $user->id);
    }
}
