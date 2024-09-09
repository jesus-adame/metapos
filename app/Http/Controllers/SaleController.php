<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
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

        $sale = Sale::with([
            'customer',
            'seller',
            'products' => function ($query) {
                $query->withPivot('quantity', 'price', 'has_taxes', 'tax'); // Asegúrate de incluir los pivotes aquí
            },
            'payments',
            'cashRegister',
            'cashFlows',
        ])->find($sale->id);

        return inertia('Sales/Show', [
            'sale' => $sale,
        ]);
    }

    public function generateTicket($id)
    {
        $sale = Sale::with('products')->findOrFail($id);

        $location = Auth::user()->location;

        $company_name = $location->name;
        $company_address = $location->address;
        $company_phone = $location->phone_number;
        $company_email = $location->email;
        $company_rfc = $location->rfc;

        $date = $sale->created_at;
        $date->setTimezone('America/Mexico_City');

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
                'date',
            ));

        return $pdf->stream('sale_ticket.pdf');
    }
}
