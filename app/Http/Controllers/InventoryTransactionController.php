<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class InventoryTransactionController extends Controller
{
    public function index()
    {
        return inertia('InventoryTransactions/Index');
    }
}
