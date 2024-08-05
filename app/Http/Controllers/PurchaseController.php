<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')->get();
        return Inertia::render('Purchases/Index', ['purchases' => $purchases]);
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return Inertia::render('Purchases/Create', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'total' => 0, // Calcularemos el total a continuación
            'purchase_date' => Carbon::now(),
        ]);

        $total = 0;
        foreach ($request->products as $product) {
            $productData = Product::find($product['id']);
            $quantity = $product['quantity'];
            $price = $product['price'];

            $purchase->products()->attach($productData, [
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $total += $price * $quantity;
        }

        // Actualizar el total de la compra
        $purchase->update(['total' => $total]);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }
}
