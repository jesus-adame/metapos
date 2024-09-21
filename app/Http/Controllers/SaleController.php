<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Services\Sales\TicketService;
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

        $sale = Sale::with([
            'customer',
            'seller',
            'products' => function ($query) {
                $query->withPivot('quantity', 'price', 'tax', 'tax_rate', 'subtotal', 'line_total'); // Asegúrate de incluir los pivotes aquí
            },
            'payments' => function ($query) {
                $query->with('paymentMethod');
            },
            'cashRegister',
            'cashFlows',
        ])->find($sale->id);

        return inertia('Sales/Show', [
            'sale' => $sale,
        ]);
    }

    public function generateTicket($id, TicketService $service)
    {
        $pdf = $service->execute($id);

        return $pdf->stream('sale_ticket.pdf');
    }

    public function ticketDownload($id, TicketService $service)
    {
        $pdf = $service->execute($id);

        return $pdf->download('sale_ticket.pdf');
    }
}
