<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;

class AssetsController extends Controller
{
    public function __invoke($path)
    {
        // Asegúrate de que la ruta sea accesible a través del enlace simbólico
        return response()->file(storage_path("app/publissadc/{$path}"));
    }
}
