<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Exporter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Sales\TicketService;
use App\Models\Sale;
use App\Models\Permission;
use App\Exports\SalesExport;

class SaleController extends Controller
{
    private $exporter;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

    public function export(Request $request)
    {
        Gate::authorize(Permission::VIEW_SALES);

        $startDate = $request->dates[0] ?? null;
        $endDate = $request->dates[1] ?? null;
        $location = $request->user()->location;

        if (!is_null($startDate) && !is_null($endDate)) {
            $splitSince = explode('T', $startDate);
            $splitUntil = explode('T', $endDate);

            $sinceStr = $splitSince[0] . ' 00:00:00 ' . $location->timezone;
            $untilStr = $splitUntil[0] . ' 23:59:59 ' . $location->timezone;

            $startDate = Carbon::parse($sinceStr);
            $endDate = Carbon::parse($untilStr);
        }

        return $this->exporter->download(new SalesExport($request->all(), $startDate, $endDate), 'sales.xlsx');
    }

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
