<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\InventoryTransacctions\CreateInventoryTransaction;
use App\Models\Location;
use App\Models\InventoryTransaction;
use App\Http\Requests\StoreInventoryRequest;
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

    public function store(StoreInventoryRequest $request, CreateInventoryTransaction $service)
    {
        $location = Location::find($request->location_id);

        $transaction = $service->execute(
            $location,
            $request->type,
            $request->product_id,
            $request->quantity,
            $request->applicated_at,
            $request->description,
        );

        return response()->json([
            'message' => 'Transacción registrada correctamente',
            'transaction' => $transaction,
        ]);
    }
}
