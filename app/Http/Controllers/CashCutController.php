<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CashCutController extends Controller
{
    public function index()
    {
        return Inertia::render('CashCuts/Index');
    }

    public function create()
    {
        return Inertia::render('CashCuts/Create');
    }
}
