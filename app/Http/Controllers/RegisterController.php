<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request; // Illuminate -> Funciones de Laravel
use Illuminate\Support\Facades\Hash; // Facades -> Conjunto de funciones que hacen algo muy específico

class RegisterController extends Controller
{
    //

    public function index() {

        return view('auth.register');
    }

    public function store(Request $request) {

        // dd($request);
        // dd($request->get('username'));

        // Modificar el Request
        $request->request->add(['username' => Str::slug( $request->username )]);

        // Validación
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        // Equivalente a INSERT INTO en Laravel
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make( $request->password ) // Hash::make para hashear passwords en Laravel
        ]);

        // Autenticar Usuario
        auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        // Otra forma de autenticar
        // auth()->attempt($request->only('email', 'password'));

        // Redireccionar
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
