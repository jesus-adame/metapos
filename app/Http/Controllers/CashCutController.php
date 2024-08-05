<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\CashCut;

class CashCutController extends Controller
{
    public function index()
    {
        return Inertia::render('CashCuts/Index');
    }

    public function create()
    {
        return Inertia::render('CashCuts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cut_date' => 'required|date',
            'cut_end_date' => 'required|date',
            'timezone' => 'required|string',
        ]);

        $cashRegister = auth()->user();

        // Calcular entradas y salidas hasta la fecha del corte
        $cutDate = Carbon::createFromTimeString($request->cut_date);
        $cutEndDate = Carbon::createFromTimeString($request->cut_end_date);

        $cutDate->setHours(0);
        $cutEndDate->setHours(0);

        $totalEntries = CashFlow::where('type', 'entry')
            ->where('cash_register_id', $cashRegister->id)
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $totalExits = CashFlow::where('type', 'exit')
            ->where('cash_register_id', $cashRegister->id)
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $finalBalance = $totalEntries - $totalExits;

        CashCut::create([
            'total_entries' => $totalEntries,
            'total_exits' => $totalExits,
            'final_balance' => $finalBalance,
            'cut_date' => $cutDate->format('Y-m-d'),
            'cut_end_date' => $cutEndDate->format('Y-m-d'),
            'cash_register_id' => $cashRegister->id,
        ]);

        return redirect()->route('cash-cuts.index')->with('success', 'Cash cut recorded successfully.');
    }
}
