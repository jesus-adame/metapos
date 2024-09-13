<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $location = $request->user()->location;
        $perPage = $request->input('rows', 10);
        $expenses = Expense::with('expenseCategory')
            ->where('location_id', $location->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount',
        ]);

        return response()->json();
    }
}
