<?php

namespace App\Services\CashCuts;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CashRegister;
use App\Models\CashFlow;
use App\Models\CashCut;

class CreateCashCutService
{
    public function execute(User $user, Carbon $cutDate, Carbon $cutEndDate, CashRegister $cashRegister, array $params)
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

        $expected_amount = $finalBalance;
        $real_amount = $params['cash'] + $params['card'] + $params['transfer'];
        $real_final_balance = $real_amount - $expected_amount;

        $cashCut = CashCut::create([
            'user_id' => $user->id,
            'total_entries' => $totalEntries,
            'total_exits' => $totalExits,
            'final_balance' => $finalBalance,
            'cash_amount' => $cashAmount,
            'card_amount' => $cardAmount,
            'transfer_amount' => $transferAmount,
            'cut_date' => $cutDate,
            'cut_end_date' => $cutEndDate,
            'cash_register_id' => $cashRegister->id,
            'real_cash_amount' => $params['cash'],
            'real_card_amount' => $params['card'],
            'real_transfer_amount' => $params['transfer'],
            'real_total' => $real_amount,
            'real_final_balance' => $real_final_balance,
        ]);

        return $cashCut;
    }
}
