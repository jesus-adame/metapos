<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\Purchase;
use App\Models\Permission;

class PurchaseController extends Controller
{
    public function index()
    {
        Gate::authorize(Permission::VIEW_PURCHASES);

        $purchases = Purchase::with('supplier', 'buyer', 'location')
            ->orderBy('updated_at', 'desc')
            ->get();

        return inertia('Purchases/Index', ['purchases' => $purchases]);
    }

    public function show(int $purchaseId)
    {
        Gate::authorize(Permission::VIEW_PURCHASES);

        $purchase = Purchase::with('products')
            ->with('supplier')
            ->with('buyer')
            ->with('location')
            ->where('id', $purchaseId)
            ->first();

        return inertia('Purchases/Show', [
            'purchase' => $purchase
        ]);
    }

    public function create()
    {
        return inertia('Purchases/Create');
    }
}
