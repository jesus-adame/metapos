<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

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
}
