<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Branch;
use App\Http\Requests\CreatePurchaseRequest;

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

    public function store(CreatePurchaseRequest $request, CreateInventoryTransaction $inventoryService)
    {
        $purchaseDate = Carbon::createFromTimeString($request->purchase_date);
        $buyerId = Auth::user()->id;
        $branch = Branch::find(Auth::user()->branch_id);

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'buyer_id' => $buyerId,
            'branch_d' => $branch->id,
            'total' => 0, // Calcularemos el total a continuación
            'purchase_date' => $purchaseDate->format('Y-m-d'),
            'status' => 'paid',
        ]);

        $total = 0;
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['quantity'];
            $price = $productData['price'];

            $purchase->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $total += $price * $quantity;

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
