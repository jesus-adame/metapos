<?php

namespace App\Http\Controllers;

use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier', 'buyer', 'branch')
            ->orderBy('updated_at', 'desc')
            ->get();

        return inertia('Purchases/Index', ['purchases' => $purchases]);
    }

    public function show(int $purchaseId)
    {
        $purchase = Purchase::with('products')->where('id', $purchaseId)->first();

        return inertia('Purchases/Show', [
            'purchase' => $purchase
        ]);
    }

    public function create()
    {
        return inertia('Purchases/Create');
    }
}
