<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Suppliers\AllSuppliersService;
use App\Models\Supplier;

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

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }
}
