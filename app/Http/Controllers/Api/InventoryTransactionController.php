<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\InventoryTransaction;
use App\Http\Controllers\Controller;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);

        $paginate = InventoryTransaction::with('product')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        // Calcular el saldo en inventario
        $totalEntries = InventoryTransaction::where('type', 'entry')->sum('quantity');
        $totalExits = InventoryTransaction::where('type', 'exit')->sum('quantity');
        $balance = $totalEntries - $totalExits;

        return response()->json([
            'paginate' => $paginate,
            'balance' => $balance,
        ]);
    }
}
