<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\Customers\AllCustomersService;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(AllCustomersService $service)
    {
        $customers = $service->execute();

        return inertia('Customers/Index', [
            'title' => 'Clientes',
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
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

        Customer::create($request->all());

        return redirect()->route('users.index')->with('success', 'Customer created successfully.');
    }
}
