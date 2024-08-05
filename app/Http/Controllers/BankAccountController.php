<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::all();
        return inertia('BankAccounts/Index', ['bankAccounts' => $bankAccounts]);
    }

    public function create()
    {
        return inertia('BankAccounts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        BankAccount::create($request->all());

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account created successfully.');
    }

    public function edit(BankAccount $bankAccount)
    {
        return inertia('BankAccounts/Edit', ['bankAccount' => $bankAccount]);
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $bankAccount->update($request->all());

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account updated successfully.');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account deleted successfully.');
    }
}
