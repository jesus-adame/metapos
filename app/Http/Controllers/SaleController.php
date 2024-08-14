<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sale;
use App\Models\Permission;

class SaleController extends Controller
{
    public function index()
    {
        Gate::authorize(Permission::VIEW_SALES);

        return inertia('Sales/Index');
    }

    public function create()
    {
        Gate::authorize(Permission::CREATE_SALES);

        return inertia('Sales/Create');
    }

    public function show(Sale $sale)
    {
        Gate::authorize(Permission::VIEW_SALES);

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

    public function generateTicket($id)
    {
        $sale = Sale::with('products')->findOrFail($id);
        $pdf = Pdf::setPaper('b7', 'portrait')->loadView('tickets.test', compact('sale'));

        return $pdf->stream('sale_ticket.pdf');
    }
}
