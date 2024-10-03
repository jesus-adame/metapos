<?php

namespace App\Http\Controllers;

class CashFlowController extends Controller
{
    public function index()
    {
        return inertia('CashFlows/Index');
    }

    public function create()
    {
        return inertia('CashFlows/Create');
    }
}
