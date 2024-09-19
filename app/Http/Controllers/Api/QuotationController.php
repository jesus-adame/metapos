<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Quotations\RegisterQuotationService;
use App\Models\Quotation;
use App\Models\Permission;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Controllers\Controller;

class QuotationController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize(Permission::VIEW_SALES);

        $since = $request->dates[0] ?? null;
        $until = $request->dates[1] ?? null;

        $location = $request->user()->location;

        $perPage = $request->input('rows', 10);
        $builder = Quotation::with('customer', 'seller');

        if (!is_null($since) && !is_null($until)) {
            $splitSince = explode('T', $since);
            $splitUntil = explode('T', $until);

            $sinceStr = $splitSince[0] . ' 00:00:00 ' . $location->timezone;
            $untilStr = $splitUntil[0] . ' 23:59:59 ' . $location->timezone;

            $since = Carbon::parse($sinceStr);
            $builder->where('created_at', '>=', $since->utc());

            $until = Carbon::parse($untilStr);
            $builder->where('created_at', '<=', $until->utc());
        }

        if (!is_null($request->status)) {
            $builder->where('status', $request->status);
        }

        $totalSales = $builder->sum('total');
        $builder->orderBy('created_at', 'desc');
        $paginate = $builder->paginate($perPage);

        $response = $paginate->toArray();
        $response['totalSales'] = $totalSales;

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotationRequest $request, RegisterQuotationService $service)
    {
        $sellerId = Auth::id();

        $response = $service->execute(
            $request->customer_id,
            $sellerId,
            $request->products,
            $request->payment_methods,
            $request->discount,
        );

        if ($response['status'] == 'error') {
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
