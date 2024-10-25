<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Category;
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
                    'label' => 'Semana anterior',
                    'backgroundColor' => '#a0a0a0',
                    'borderColor' => 'gray',
                    'tension' => 0.4,
                    'fill' => false,
                    'data' => $pastSales,
                ],
                [
                    'label' => 'Semana actual',
                    'backgroundColor' => '#ff5151',
                    'borderColor' => 'red',
                    'tension' => 0.4,
                    'fill' => true,
                    'data' => $currentSales,
                ]
            ]
        ];

        return response()->json($chartData);
    }

    public function salesByCategory()
    {
        $today = Carbon::today();

        // $categories = Category::with();

        $products = Product::whereHas('sales', function ($query) use ($today) {
            $query->whereDate('sales.created_at', $today);
        })
            ->with(['sales' => function ($query) use ($today) {
                $query->whereDate('sales.created_at', $today)
                    ->select('sales.*', DB::raw('SUM(product_sale.quantity) as total_quantity'))
                    ->groupBy('sales.id');
            }])
            ->get();

        return response()->json($products);
    }

    public function inventoryValues()
    {
        $products = Product::with('inventories')->get();

        $totalValue = $products->sum(function (Product $product) {
            $inventories = $product->inventories->sum(function (Inventory $inventory) {
                return $inventory->quantity;
            });

            return $product->price * $inventories;
        });

        $totalCost = $products->sum(function (Product $product) {
            return $product->cost;
        });

        $totalMargin = $totalValue - $totalCost;

        return response()->json([
            'totalValue' => $totalValue,
            'totalCost' => $totalCost,
            'totalMargin' => $totalMargin,
        ]);
    }
}
