<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function __construct() {
        
        $this->middleware('auth');
    }
    
    public function __invoke() {

        // Obtener a quienes seguimos (id para filtrar en el modelo de Posts)
        // pluck() -> Nos va a traer unicamente los campos que necesitemos del array de atributos de Usuario
        $ids = auth()->user()->followings->pluck('id')->toArray();
        
        $posts = Post::whereIn('user_id', $ids)->latest()->paginate(15);

        
        return view('home', [
            'posts' => $posts
        ]);
    }
}
