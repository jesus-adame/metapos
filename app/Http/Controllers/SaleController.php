<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Sales\RegisterSaleService;
use App\Services\Sales\AllSalesService;
use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;

class SaleController extends Controller
{
    public function index(AllSalesService $service)
    {
        $sales = $service->execute();

        return inertia('Sales/Index', [
            'title' => 'Ventas',
            'sales' => $sales
        ]);
    }

    public function create()
    {
        return inertia('Sales/Create');
    }

    public function show(Sale $sale)
    {
        $sale = Sale::with('customer')
            ->with('seller')
            ->with('products')
            ->with('payments')
            ->with('cashRegister')
            ->with('cashFlows')
            ->find($sale->id);

        return inertia('Sales/Show', [
            'sale' => $sale,
        ]);
    }

    public function store(StoreSaleRequest $request, RegisterSaleService $service): Response
    {
        $sellerId = auth()->id();
        $cash_register_id = auth()->user()->cash_register_id;

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

    public function generateTicket($id)
    {
        $sale = Sale::with('products')->findOrFail($id);
        $pdf = Pdf::setPaper('b7', 'portrait')->loadView('tickets.test', compact('sale'));

        return $pdf->stream('sale_ticket.pdf');
    }
}
