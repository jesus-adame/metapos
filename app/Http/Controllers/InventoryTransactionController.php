<?php

namespace App\Http\Controllers;

use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\InventoryTransaction;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Controllers\Controller;

class InventoryTransactionController extends Controller
{
    public function index()
    {
        $transactions = InventoryTransaction::with('product')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Calcular el saldo en inventario
        $totalEntries = InventoryTransaction::where('type', 'entry')->sum('quantity');
        $totalExits = InventoryTransaction::where('type', 'exit')->sum('quantity');
        $balance = $totalEntries - $totalExits;

        return inertia('InventoryTransactions/Index', [
            'transactions' => $transactions,
            'balance' => $balance,
        ]);
    }

    public function store(StoreInventoryRequest $request, CreateInventoryTransaction $service)
    {
        $transaction = $service->execute(
            $request->type,
            $request->product_id,
            $request->quantity,
            $request->transaction_date,
            $request->description,
        );

        return response()->json([
            'message' => 'Transacción registrada correctamente',
            'transaction' => $transaction,
        ]);
    }
}
