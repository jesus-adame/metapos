<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashCut extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_entries',
        'total_exits',
        'final_balance',
        'cut_date',
        'cut_end_date',
        'cash_register_id'
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }
}
