<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Models\Sale;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    public function salesByWeek(Request $request)
    {
        $location = $request->user()->location;
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now($location->timezone)->startOfWeek();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now($location->timezone)->endOfWeek();

        $currentWeek = CarbonPeriod::create($startDate, '1 day', $endDate);

        $labels = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $currentSales = [];

        /** @var Carbon $weekDay */
        foreach ($currentWeek as $weekDay) {
            $startDay = $weekDay->utc()->startOfDay()->toIsoString();
            $endDay = $weekDay->utc()->endOfDay()->toIsoString();

            $currentSales[] = Sale::where('created_at', '>=', $startDay)
                ->where('created_at', '<=', $endDay)
                ->count();
        }

        $pastSales = [];
        $pastWeek = CarbonPeriod::create($startDate->subWeek(), '1 day', $endDate->subWeek());

        /** @var Carbon $weekDay */
        foreach ($pastWeek as $weekDay) {
            $startDay = $weekDay->utc()->startOfDay()->toIsoString();
            $endDay = $weekDay->utc()->endOfDay()->toIsoString();

            $pastSales[] = Sale::where('created_at', '>=', $startDay)
                ->where('created_at', '<=', $endDay)
                ->count();
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Periodo anterior',
                    'backgroundColor' => 'gray',
                    'borderColor' => 'gray',
                    'data' => $pastSales
                ],
                [
                    'label' => 'Periodo actual',
                    'backgroundColor' => 'red',
                    'borderColor' => 'red',
                    'data' => $currentSales
                ]
            ]
        ];

        return response()->json($chartData);
    }
}
