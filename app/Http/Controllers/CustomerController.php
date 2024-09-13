<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return inertia('Customers/Index');
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    public function show(Customer $customer)
    {
        return inertia('Customers/Show', [
            'customer' => $customer
        ]);
    }
}
