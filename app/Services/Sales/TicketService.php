<?php

namespace App\Services\Sales;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sale;

class TicketService
{
    public function execute($id)
    {
        $sale = Sale::with([
            'products' => function ($query) {
                $query->withPivot('quantity', 'price', 'has_taxes', 'tax');
            }
        ])->findOrFail($id);

        $location = $sale->cashRegister->location;

        $company_name = $location->name;
        $company_address = $location->address;
        $company_phone = $location->phone_number;
        $company_email = $location->email;
        $company_rfc = $location->rfc;

        $totalPayments = $sale->payments()->sum('amount');
        $taxes = 0;

        foreach ($sale->products as $product) {
            $pivot = $product->pivot;
            $taxes += $pivot->price * $pivot->tax / 100;
        }

        $date = $sale->created_at;
        $date->setTimezone($location->timezone);

        // Suponiendo que quieres una relación de aspecto de 1:1.5
        $ancho_mm = 45;
        $relacion_aspecto = 2.5;

        // Convertir mm a puntos
        $ancho_puntos = $ancho_mm * 3;
        $alto_puntos = $ancho_puntos * $relacion_aspecto;

        return Pdf::setPaper(array(0, 0, $ancho_puntos, $alto_puntos))
            ->loadView('tickets.print', compact(
                'sale',
                'taxes',
                'totalPayments',
                'ancho_mm',
                'company_name',
                'company_address',
                'company_phone',
                'company_email',
                'company_rfc',
                'date',
            ))
        ;
    }
}
