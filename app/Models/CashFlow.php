<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', // ['entry', 'exit']
        'amount',
        'description',
        'date',
        'method',
        'cashable_id',
        'cashable_type',
        'cash_register_id'
    ];

    public static function getBalance(int $cashRegisterId)
    {
        $totalEntries = self::where('type', 'entry')->where('cash_register_id', $cashRegisterId)->sum('amount');
        $totalExits = self::where('type', 'exit')->where('cash_register_id', $cashRegisterId)->sum('amount');
        return $totalEntries - $totalExits;
    }

    public static function getBalanceByMethod(int $cashRegisterId, string $method)
    {
        $totalEntries = self::where('type', 'entry')
            ->where('cash_register_id', $cashRegisterId)
            ->where('method', $method)
            ->sum('amount');
        $totalExits = self::where('type', 'exit')
            ->where('cash_register_id', $cashRegisterId)
            ->where('method', $method)
            ->sum('amount');
        return $totalEntries - $totalExits;
    }

    public function cashable()
    {
        return $this->morphTo();
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }
}
