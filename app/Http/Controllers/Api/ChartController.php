<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sale;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    public function salesByWeek(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subWeeks(4)->startOfWeek());
        $endDate = $request->input('end_date', Carbon::now()->endOfWeek());

        $sales = Sale::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('WEEK(date, 1) as week'),
            DB::raw('SUM(total) as total_sales')
        )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('year', 'week')
            ->orderBy('year', 'asc')
            ->orderBy('week', 'asc')
            ->get();

        $formattedSales = $sales->map(function ($sale) {
            $weekStart = Carbon::now()->setISODate($sale->year, $sale->week);
            return [
                'week_start' => $weekStart->format('Y-m-d'),
                'week_end' => $weekStart->endOfWeek()->format('Y-m-d'),
                'total_sales' => $sale->total_sales,
            ];
        });

        return response()->json($formattedSales);
    }
}
