<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CashFlow;
use App\Http\Controllers\Controller;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $cashRegisterId = auth()->user()->cashRegister->id;

        $paginate = CashFlow::with('cashRegister')
            ->orderBy('updated_at', 'desc')
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
}
