<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
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

        $company_name = Setting::where('key', Setting::COMPANY_NAME)->first()->value;
        $company_address = Setting::where('key', Setting::COMPANY_ADDRESS)->first()->value;
        $company_phone = Setting::where('key', Setting::COMPANY_PHONE)->first()->value;
        $company_email = Setting::where('key', Setting::COMPANY_EMAIL)->first()->value;
        $company_rfc = Setting::where('key', Setting::COMPANY_RFC)->first()->value;

        // Suponiendo que quieres una relación de aspecto de 1:1.5
        $ancho_mm = 45;
        $relacion_aspecto = 2.5;

        // Convertir mm a puntos
        $ancho_puntos = $ancho_mm * 3;
        $alto_puntos = $ancho_puntos * $relacion_aspecto;

        $pdf = Pdf::setPaper(array(0, 0, $ancho_puntos, $alto_puntos))
            ->loadView('tickets.print', compact(
                'sale',
                'ancho_mm',
                'company_name',
                'company_address',
                'company_phone',
                'company_email',
                'company_rfc',
            ));

        return $pdf->stream('sale_ticket.pdf');
    }
}
