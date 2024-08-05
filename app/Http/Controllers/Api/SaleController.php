<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|decimal:0,4',
        ]);

        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'total' => 0, // We'll calculate the total below
        ]);

        $total = 0;
        foreach ($request->products as $product) {
            $productData = Product::find($product['id']);
            $quantity = $product['quantity'];
            $price = $product['price'];

            $sale->products()->attach($productData, [
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $total += $price * $quantity;
        }

        // Update the total amount of the sale
        $sale->update(['total' => $total]);

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }
}
