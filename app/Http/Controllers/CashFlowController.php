<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashFlow;

class CashFlowController extends Controller
{
    public function index()
    {
        return Inertia::render('CashFlows/Index');
    }

    public function create()
    {
        return Inertia::render('CashFlows/Create');
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

        $cashRegisterId = auth()->user()->cash_register_id;

        CashFlow::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'method' => $request->method,
            'date' => Carbon::createFromTimeString($request->date),
            'cash_register_id' => $cashRegisterId,
        ]);

        return redirect()->route('cash-flows.index')->with('success', 'Cash flow recorded successfully.');
    }
}
