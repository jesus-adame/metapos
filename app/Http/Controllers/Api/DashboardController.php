<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

        $user = Auth::user();
        $timezone = $user->location->timezone;
        $today = Carbon::now($timezone);
        $firstOfTheMonth = Carbon::now($timezone)->firstOfMonth();

        $salesAmount = Sale::where('status', 'completed')
            ->where('created_at', '>=', $firstOfTheMonth->utc())
            ->where('created_at', '<=', $today->utc())
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
