<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\CashCut;
use App\Http\Controllers\Controller;

class CashCutController extends Controller
{
    public function index(Request $request)
    {
        $cashRegister = Auth::user()->cashRegister;

        $perPage = $request->input('rows', 10);
        $cashCuts = CashCut::with('cashRegister')
            ->where('cash_register_id', $cashRegister->id)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return response()->json($cashCuts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cut_date' => 'required|date',
            'cut_end_date' => 'required|date',
            'timezone' => 'required|string',
        ]);

        $cashRegister = Auth::user()->cashRegister;

        // Calcular entradas y salidas hasta la fecha del corte
        $cutDate = Carbon::createFromTimeString($request->cut_date);
        $cutEndDate = Carbon::createFromTimeString($request->cut_end_date);

        $cutDate->setHours(0);
        $cutEndDate->setHours(23)
            ->setMinutes(59)
            ->setSeconds(59);

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

        return response()->json([
            'message' => 'Corte de caja registrado.'
        ]);
    }
}
