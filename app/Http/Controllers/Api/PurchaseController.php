<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Purchases\RegisterPurchaseService;
use App\Http\Requests\CreatePurchaseRequest;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function store(CreatePurchaseRequest $request, RegisterPurchaseService $purchaseService)
    {
        $purchaseDate = Carbon::createFromTimeString($request->purchase_date);
        $buyerId = Auth::user()->id;
        $cash_register_id = Auth::user()->cash_register_id;

        $purchaseService->execute(
            $request->supplier_id,
            $buyerId,
            $purchaseDate,
            $request->products,
            $request->update_cash_register,
            $cash_register_id,
            $request->payment_methods,
        );

        return response()->json([
            'messsage' => 'Compra registrada correctamente.'
        ], Response::HTTP_CREATED);
    }
}
