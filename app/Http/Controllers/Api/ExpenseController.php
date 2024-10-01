<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Permission;
use App\Models\Expense;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize(Permission::VIEW_EXPENSES);

        $location = $request->user()->location;
        $perPage = $request->input('rows', 10);
        $expenses = Expense::with(['expenseCategory', 'creator', 'location'])
            ->where('location_id', $location->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        Gate::authorize(Permission::CREATE_EXPENSES);

        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'required',
        ]);

        $expense = new Expense();

        $expense->expense_category_id = $request->category_id;
        $expense->creator_id = $request->user()->id;
        $expense->location_id = $request->user()->location->id;
        $expense->amount = $request->amount;
        $expense->expense_date = Carbon::parse($request->expense_date);
        $expense->save();

        return response()->json([
            'message' => 'Gasto registrado correctamente',
            'data' => $expense,
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        Gate::authorize(Permission::UPDATE_EXPENSES);

        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'required',
        ]);

        $expense->expense_category_id = $request->category_id;
        $expense->creator_id = $request->user()->id;
        $expense->location_id = $request->user()->location->id;
        $expense->amount = $request->amount;
        $expense->expense_date = Carbon::parse($request->expense_date);
        $expense->save();

        return response()->json([
            'message' => 'Gasto registrado correctamente',
            'data' => $expense,
        ]);
    }

    public function destroy(Expense $expense)
    {
        Gate::authorize(Permission::DELETE_EXPENSES);

        $expense->delete();

        return response()->json([
            'message' => 'Gasto eliminado correctamente',
        ]);
    }
}
