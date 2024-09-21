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

        $purchases = Purchase::with('supplier', 'buyer', 'location', 'payments')
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
            ->with([
                'products' => function ($query) {
                    $query->withPivot('quantity', 'price', 'tax', 'tax_rate', 'subtotal', 'line_total'); // Asegúrate de incluir los pivotes aquí
                },
                'payments' => function ($query) {
                    $query->with('paymentMethod');
                },
            ])
            ->where('id', $purchaseId)
            ->first();

        return inertia('Purchases/Show', [
            'purchase' => $purchase
        ]);
    }

    public function create()
    {
        Gate::authorize(Permission::CREATE_PURCHASES);

        return inertia('Purchases/Create');
    }
}
