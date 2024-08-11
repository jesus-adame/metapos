<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

        // Calcular el saldo en caja
        $totalEntries = CashFlow::where('type', 'entry')->where('cash_register_id', $cashRegisterId)->sum('amount');
        $totalExits = CashFlow::where('type', 'exit')->where('cash_register_id', $cashRegisterId)->sum('amount');
        $balance = $totalEntries - $totalExits;

        return response()->json([
            'paginate' => $paginate,
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

        $date = Carbon::createFromTimeString($request->date);
        $cashRegisterId = Auth::user()->cash_register_id;

        CashFlow::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'method' => $request->method,
            'date' => $date->format('Y-m-d 00:00:00'),
            'cash_register_id' => $cashRegisterId,
        ]);

        return redirect()->route('cash-flows.index')->with('success', 'Cash flow recorded successfully.');
    }
}
