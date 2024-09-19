<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    public function index()
    {
        return inertia('Currencies/Index');
    }
}
