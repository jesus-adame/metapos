<?php

namespace App\Http\Controllers\Assets;

use Stancl\Tenancy\Controllers\TenantAssetsController as Controller;

class TenantAssetsController extends Controller
{
    public function __invoke($path)
    {
        // Asegúrate de que la ruta sea accesible a través del enlace simbólico

        dd('llega');

        return $this->asset($path);
    }
}
