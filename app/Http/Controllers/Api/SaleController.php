<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Sales\RegisterSaleService;
use App\Services\Sales\CancelSaleService;
use App\Models\Sale;
use App\Models\Permission;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\CancelSaleRequest;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize(Permission::VIEW_SALES);

        $perPage = $request->input('rows', 10);
        $cashRegisterId = Auth::user()->cash_register_id;
        $builder = Sale::with('customer', 'seller', 'cashRegister');

        if (!is_null($cashRegisterId)) {
            $builder->where('cash_register_id', $cashRegisterId);
        }

        $paginate = $builder->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($paginate);
    }

    public function store(StoreSaleRequest $request, RegisterSaleService $service): Response
    {
        $sellerId = Auth::id();
        $cashRegisterId = Auth::user()->cash_register_id;

        $response = $service->execute(
            $request->customer_id,
            $sellerId,
            $request->products,
            $request->payment_methods,
            $cashRegisterId,
            $request->discount,
        );

        if ($response['status'] == 'error') {
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        return response()->json($response, Response::HTTP_CREATED);
    }

    public function cancel(CancelSaleRequest $request, string $saleId, CancelSaleService $service)
    {
        $sale = Sale::with('products')->find($saleId);

        $response = $service->execute($sale);

        return response()->json($response);
    }
}
