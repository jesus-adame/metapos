<?php

namespace App\Http\Controllers;

use App\Services\Suppliers\AllSuppliersService;

class SupplierController extends Controller
{
    public function index(AllSuppliersService $service)
    {
        $suppliers = $service->execute();

        return inertia('Suppliers/Index', [
            'title' => 'Proveedores',
            'suppliers' => $suppliers
        ]);
    }

    public function create()
    {
        return inertia('Suppliers/Create');
    }
}
