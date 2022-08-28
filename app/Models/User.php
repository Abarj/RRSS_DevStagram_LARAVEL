<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // $fillable -> Los datos que esperamos que el usuario nos dé
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones

    public function posts() {

        // One to Many | Un usuario -> Muchos posts
        return $this->hasMany(Post::class);
    }

    public function likes() {

        // One to Many | Un usuario -> Muchos likes
        return $this->hasMany(Like::class);
    }

    // Almacena los seguidores de un Usuario
    public function followers() {

        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id'); // Especificamos la tabla y las llaves foráneas ya que nos hemos salido de la convención de Laravel
    }

    // Almacenar los que seguimos
    public function followings() {

        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id'); // Cambiamos el orden para obtener la misma funcionalidad a la inversa
    }

    // Comprobar si un usuario ya sigue a otro
    public function siguiendo(User $user) {

        return $this->followers->contains( $user->id );
    }
}
