<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Expense;
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
            $inventories = $product->inventories->sum(function (Inventory $inventory) {
                return $inventory->quantity;
            });

            return $product->cost * $inventories;
        });

        $totalMargin = $totalValue - $totalCost;

        return response()->json([
            'totalValue' => $totalValue,
            'totalCost' => $totalCost,
            'totalMargin' => $totalMargin,
        ]);
    }

    public function utilityBalance(Request $request)
    {
        $since = $request->dates[0] ?? null;
        $until = $request->dates[1] ?? null;
        $location = $request->user()->location;

        if (is_null($since) || is_null($until)) {
            return response()->json([
                'sales' => 0,
                'purchases' => 0,
                'expenses' => 0,
                'utility' => 0,
            ]);
        }

        $splitSince = explode('T', $since);
        $splitUntil = explode('T', $until);

        $sinceStr = $splitSince[0] . ' 00:00:00 ' . $location->timezone;
        $untilStr = $splitUntil[0] . ' 23:59:59 ' . $location->timezone;

        $since = Carbon::parse($sinceStr);
        $until = Carbon::parse($untilStr);

        $salesAmount = Sale::where('created_at', '>=', $since->utc())
            ->where('created_at', '<=', $until->utc())
            ->sum('total');

        $purchasesAmount = Purchase::where('created_at', '>=', $since->utc())
            ->where('created_at', '<=', $until->utc())
            ->sum('total');

        $expensesAmount = Expense::where('created_at', '>=', $since->utc())
            ->where('created_at', '<=', $until->utc())
            ->sum('amount');

        $totalExpenses = $purchasesAmount + $expensesAmount;
        $utilityAmount = $salesAmount - $totalExpenses;

        return response()->json([
            'sales' => $salesAmount,
            'purchases' => $purchasesAmount,
            'expenses' => $expensesAmount,
            'utility' => $utilityAmount,
        ]);
    }
}
