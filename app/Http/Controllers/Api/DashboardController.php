<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Permission;
use App\Models\Customer;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function resume(): JsonResponse
    {
        Gate::allows(Permission::VIEW_FINANCES);

        $salesAmount = Sale::where('status', 'completed')
            ->sum('total');

        $purchasesAmount = Purchase::where('status', 'completed')
            ->sum('total');

        $customersAmount = Customer::count();
        $usersAmount = User::where('name', '!=', 'Admin')->count();

        return response()->json([
            'salesAmount' => $salesAmount,
            'purchasesAmount' => $purchasesAmount,
            'customersAmount' => $customersAmount,
            'usersAmount' => $usersAmount,
        ]);
    }
}