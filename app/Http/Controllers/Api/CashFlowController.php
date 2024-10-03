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

        // Calcular el saldo en caja
        $balance = CashFlow::getBalance($cashRegisterId);
        $entries = CashFlow::getEntries($cashRegisterId);
        $exits = CashFlow::getExits($cashRegisterId);

        $cashBalance = CashFlow::getBalanceByMethod($cashRegisterId, 'cash');
        $cashEntries = CashFlow::getEntriesByMethod($cashRegisterId, 'cash');
        $cashExits = CashFlow::getExitsByMethod($cashRegisterId, 'cash');

        $cardBalance = CashFlow::getBalanceByMethod($cashRegisterId, 'card');
        $cardEntries = CashFlow::getEntriesByMethod($cashRegisterId, 'card');
        $cardExits = CashFlow::getExitsByMethod($cashRegisterId, 'card');

        $transferBalance = CashFlow::getBalanceByMethod($cashRegisterId, 'transfer');
        $transferEntries = CashFlow::getEntriesByMethod($cashRegisterId, 'transfer');
        $transferExits = CashFlow::getExitsByMethod($cashRegisterId, 'transfer');

        $sales = Sale::sum('total');
        $purchases = Purchase::sum('total');
        $expenses = Expense::sum('amount');

        return response()->json([
            'sales' => $sales,
            'purchases' => $purchases,
            'expenses' => $expenses,
            'global' => [
                'entries' => $entries,
                'exits' => $exits,
                'balance' => $balance,
            ],
            'cash' => [
                'entries' => $cashEntries,
                'exits' => $cashExits,
                'balance' => $cashBalance,
            ],
            'card' => [
                'entries' => $cardEntries,
                'exits' => $cardExits,
                'balance' => $cardBalance,
            ],
            'transfer' => [
                'entries' => $transferEntries,
                'exits' => $transferExits,
                'balance' => $transferBalance,
            ],
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
