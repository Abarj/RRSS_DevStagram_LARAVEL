<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller {

    public function __construct() {
        
        $this->middleware('auth');
    }

    public function index() {
        
        return view('perfil.index');
    }

    public function store(Request $request) {

        // Modificar el Request
        $request->request->add(['username' => Str::slug( $request->username )]);
    
        $this->validate($request, [
            'username' => ['required', 'unique:users,username,' . auth()->user()->id, 'min:3', 'max:20', 'not_in:editar-perfil'],
            'email' => ['required', 'unique:users,email,' . auth()->user()->id, 'email', 'max:60']
        ]);

        if($request->imagen) {
            $imagen = $request->file('imagen'); // En memoria momentáneamente

            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $imagenServidor = Image::make($imagen); // La IMG que tenemos en memoria ya forma parte de interventionImage
            $imagenServidor->fit(1000, 1000); // Fit -> Efecto de InterventionImage

            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath); // Guardamos la IMG que teníamos en memoria una vez procesada
        }

        // Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
        $usuario->email = $request->email;
        $usuario->save();

        // Cambiar contraseña
        if($request->old_password || $request->password) {
            $this->validate($request, [
                'password' => 'required|confirmed|min:6'
            ]);

            if(Hash::check($request->old_password, auth()->user()->password)) {
                $usuario->password = Hash::make($request->password);
                $usuario->save();
                return redirect()->route('posts.index', $usuario->username)->with('mensaje', 'Contraseña cambiada correctamente');
                
            }
            else{
                return back()->with('mensaje', 'La contraseña Actual no coincide');
            }
        }
        
        // Redireccionar
        return redirect()->route('posts.index', $usuario->username); // No auth()->user() ya que podría haberlo modificado
    }
}
