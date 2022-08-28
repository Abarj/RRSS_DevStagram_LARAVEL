<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller {

    public function store(Request $request) {

        $imagen = $request->file('file'); // En memoria momentáneamente

        $nombreImagen = Str::uuid() . "." . $imagen->extension();

        $imagenServidor = Image::make($imagen); // La IMG que tenemos en memoria ya forma parte de interventionImage
        $imagenServidor->fit(1000, 1000); // Fit -> Efecto de InterventionImage

        $imagenPath = public_path('uploads') . '/' . $nombreImagen;
        $imagenServidor->save($imagenPath); // Guardamos la IMG que teníamos en memoria una vez procesada

        return response()->json(['imagen' => $nombreImagen]);

        // En la base de datos SIEMPRE SOLO se almacenan los nombres NO LAS IMÁGENES
    }
}
