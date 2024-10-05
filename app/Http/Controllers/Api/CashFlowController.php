<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\CashFlow;
use App\Http\Controllers\Controller;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $cashRegisterId = Auth::user()->cashRegister->id;

        $paginate = CashFlow::with('cashRegister')
            ->orderBy('date', 'desc')
            ->where('cash_register_id', $cashRegisterId)
            ->paginate($perPage);

        return response()->json([
            'paginate' => $paginate,
        ]);
    }

    public function resume()
    {
        $cashRegisterId = Auth::user()->cashRegister->id;

        $sales = Sale::sum('total');
        $purchases = Purchase::sum('total');
        $expenses = Expense::sum('amount');
        $balance = $sales - $purchases - $expenses;

        return response()->json([
            'sales' => $sales,
            'purchases' => $purchases,
            'expenses' => $expenses,
            'balance' => $balance,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:entry,exit',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'method' => 'required|string|in:cash,card,transfer',
            'date' => 'required|date',
        ]);

        // Calcular entradas y salidas hasta la fecha del corte
        $date = Carbon::parse($request->date);
        $cashRegisterId = Auth::user()->cash_register_id;

        CashFlow::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'method' => $request->method,
            'date' => $date,
            'cash_register_id' => $cashRegisterId,
        ]);

        return response()->json([
            'message' => 'Movimiento registrado.'
        ]);
    }
}
