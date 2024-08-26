<?php

namespace App\Services\CashCuts;

use Carbon\Carbon;
use App\Models\CashRegister;
use App\Models\CashFlow;
use App\Models\CashCut;

class CreateCashCutService
{
    public function execute(Carbon $cutDate, Carbon $cutEndDate, CashRegister $cashRegister)
    {
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

        $cashEntries = CashFlow::where('type', 'entry')
            ->where('cash_register_id', $cashRegister->id)
            ->where('method', 'cash')
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $cashExits = CashFlow::where('type', 'exit')
            ->where('cash_register_id', $cashRegister->id)
            ->where('method', 'cash')
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $cardEntries = CashFlow::where('type', 'entry')
            ->where('cash_register_id', $cashRegister->id)
            ->where('method', 'card')
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $cardExits = CashFlow::where('type', 'exit')
            ->where('cash_register_id', $cashRegister->id)
            ->where('method', 'card')
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $transferEntries = CashFlow::where('type', 'entry')
            ->where('cash_register_id', $cashRegister->id)
            ->where('method', 'transfer')
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $transferExits = CashFlow::where('type', 'exit')
            ->where('cash_register_id', $cashRegister->id)
            ->where('method', 'transfer')
            ->whereDate('date', '>=', $cutDate)
            ->whereDate('date', '<=', $cutEndDate)
            ->sum('amount');

        $cashAmount = $cashEntries - $cashExits;
        $cardAmount = $cardEntries - $cardExits;
        $transferAmount = $transferEntries - $transferExits;

        $cashCut = CashCut::create([
            'total_entries' => $totalEntries,
            'total_exits' => $totalExits,
            'final_balance' => $finalBalance,
            'cash_amount' => $cashAmount,
            'card_amount' => $cardAmount,
            'transfer_amount' => $transferAmount,
            'cut_date' => $cutDate,
            'cut_end_date' => $cutEndDate,
            'cash_register_id' => $cashRegister->id,
        ]);

        return $cashCut;
    }
}
