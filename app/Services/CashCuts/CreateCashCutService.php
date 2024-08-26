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

        $cashCut = CashCut::create([
            'total_entries' => $totalEntries,
            'total_exits' => $totalExits,
            'final_balance' => $finalBalance,
            'cut_date' => $cutDate->format('Y-m-d'),
            'cut_end_date' => $cutEndDate->format('Y-m-d'),
            'cash_register_id' => $cashRegister->id,
        ]);

        return $cashCut;
    }
}
