<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Branch;
use App\Http\Requests\CreatePurchaseRequest;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function store(CreatePurchaseRequest $request, CreateInventoryTransaction $inventoryService)
    {
        $purchaseDate = Carbon::createFromTimeString($request->purchase_date);
        $buyerId = Auth::user()->id;
        $branch = Branch::find(Auth::user()->location_id);

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'buyer_id' => $buyerId,
            'branch_d' => $branch->id,
            'total' => 0, // Calcularemos el total a continuación
            'purchase_date' => $purchaseDate->format('Y-m-d'),
            'status' => 'paid',
            'location_id' => $branch->id,
            'location_type' => $branch::class,
        ]);

        $total = 0;
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['quantity'];
            $cost = $productData['cost'];

            $purchase->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $cost,
            ]);

            $total += $cost * $quantity;

            $inventoryService->execute(
                $branch,
                'entry',
                $product->id,
                $quantity,
                $purchaseDate->format('Y-m-d'),
                'Carga por compra #' . $purchase->id
            );
        }

        // Actualizar el total de la compra
        $purchase->update(['total' => $total]);

        return response()->json([
            'messsage' => 'Compra registrada correctamente.'
        ], Response::HTTP_CREATED);
    }
}
