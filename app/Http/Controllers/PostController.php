<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller {

    public function __construct() {

        // Revisa que el usuario este autenticado para acceder a esta zona
        $this->middleware('auth')->except(['show', 'index']);
    }

    public function index(User $user) {

        $posts = Post::where('user_id', $user->id)->latest()->paginate(9);
        // Si no quisiésemos paginación (como Instagram) cambiar ->paginate() por ->get(); (Y quitar los links del template en dashboard.blade.php)
        
        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function create() {
        
        return view('posts.create');
    }

    public function store(Request $request) {

        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        // CREAR UN POST

        // Una forma
        // Post::create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen,
        //     'user_id' => auth()->user()->id
        // ]);

        // Otra forma
        // $post = new Post;
        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->user_id = auth()->user()->id;
        // $post->save();

        // MEJOR Forma para LARAVEL
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post) {

        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post) {

        $this->authorize('delete', $post);
        $post->delete();

        // Eliminar la imagen de /Uploads
        $imagen_path = public_path('uploads/' . $post->imagen);

        if(File::exists($imagen_path)) {
            unlink($imagen_path);
        }


        return redirect()->route('posts.index', auth()->user()->username);
    }
}
