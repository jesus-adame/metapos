<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

        $since = $request->dates[0] ?? null;
        $until = $request->dates[1] ?? null;

        $perPage = $request->input('rows', 10);
        $builder = Sale::with('customer', 'seller', 'cashRegister');
        $builder->orderBy('created_at', 'desc');

        if (!is_null($since) && !is_null($until)) {
            $splitSince = explode('T', $since);
            $splitUntil = explode('T', $until);

            $sinceStr = $splitSince[0] . ' 00:00:00 ' . 'America/Mexico_City';
            $untilStr = $splitUntil[0] . ' 23:59:59 ' . 'America/Mexico_City';

            $since = Carbon::parse($sinceStr);
            $builder->where('created_at', '>=', $since->utc());

            $until = Carbon::parse($untilStr);
            $builder->where('created_at', '<=', $until->utc());
        }

        if (!is_null($request->cash_register)) {
            $builder->where('cash_register_id', $request->cash_register);
        }

        if (!is_null($request->status)) {
            $builder->where('status', $request->status);
        }

        $totalSales = $builder->sum('total');
        $paginate = $builder->paginate($perPage);


        $response = $paginate->toArray();
        $response['totalSales'] = $totalSales;

        return response()->json($response);
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

    public function setCustomer(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $sale->customer_id = $request->customer_id;
        $sale->save();

        return response()->json([
            'message' => 'Cliente asignado correctamente.'
        ]);
    }

    public function cancel(CancelSaleRequest $request, string $saleId, CancelSaleService $service)
    {
        $sale = Sale::with('products')->find($saleId);

        $response = $service->execute($sale);

        return response()->json($response);
    }
}
