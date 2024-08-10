<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\Sales\RegisterSaleService;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function store(StoreSaleRequest $request, RegisterSaleService $service): Response
    {
        $sellerId = Auth::id();
        $cash_register_id = Auth::user()->cash_register_id;

        $response = $service->execute(
            $request->customer_id,
            $sellerId,
            $request->products,
            $request->payment_methods,
            $cash_register_id,
        );

        if ($response['status'] == 'error') {
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        return response()->json($response, Response::HTTP_CREATED);
    }
}
