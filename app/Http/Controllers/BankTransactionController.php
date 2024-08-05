<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankTransaction;
use App\Http\Controllers\Controller;

class BankTransactionController extends Controller
{
    public function index()
    {
        $transactions = BankTransaction::orderBy('transaction_date', 'desc')->get();

        // Calcular el saldo en banco
        $totalEntries = BankTransaction::where('type', 'entry')->sum('amount');
        $totalExits = BankTransaction::where('type', 'exit')->sum('amount');
        $balance = $totalEntries - $totalExits;

        return inertia('BankTransactions/Index', [
            'transactions' => $transactions,
            'balance' => $balance,
        ]);
    }

    public function create()
    {
        return inertia('BankTransactions/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:entry,exit',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        BankTransaction::create($request->all());

        return redirect()->route('bank-transactions.index')->with('success', 'Bank transaction recorded successfully.');
    }
}
