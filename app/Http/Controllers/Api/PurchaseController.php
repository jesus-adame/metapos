<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Purchases\RegisterPurchaseService;
use App\Models\Purchase;
use App\Http\Requests\CreatePurchaseRequest;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $location = $request->user()->location;
        $perPage = $request->input('rows', 10);
        $purchases = Purchase::with('supplier', 'buyer', 'location')
            ->where('location_id', $location->id)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return response()->json($purchases);
    }

    public function store(CreatePurchaseRequest $request, RegisterPurchaseService $purchaseService)
    {
        $buyerId = Auth::user()->id;
        $cash_register_id = Auth::user()->cash_register_id;
        $purchaseDate = Carbon::parse($request->applicated_at);

        $response = $purchaseService->execute(
            $request->supplier_id,
            $buyerId,
            $purchaseDate,
            $request->products,
            $request->update_cash_register,
            $cash_register_id,
            $request->payment_methods,
        );

        if ($response['status'] == 'error') {
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        return response()->json($response, Response::HTTP_CREATED);
    }

    public function setSupplier(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $purchase->supplier_id = $request->supplier_id;
        $purchase->save();

        return response()->json([
            'message' => 'Proveedor asignado correctamente.'
        ]);
    }
}
