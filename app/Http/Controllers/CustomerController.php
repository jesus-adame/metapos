<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\Customers\AllCustomersService;

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
}
