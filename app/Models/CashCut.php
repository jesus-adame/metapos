<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashCut extends Model
{
    use HasFactory;

    protected $fillable = [
        'cut_date',
        'cut_end_date',
        'total_entries',
        'total_exits',
        'final_balance',
        'cash_amount',
        'card_amount',
        'transfer_amount',
        'real_cash_amount',
        'real_card_amount',
        'real_transfer_amount',
        'real_total',
        'real_final_balance',
        'user_id',
        'cash_register_id',
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
