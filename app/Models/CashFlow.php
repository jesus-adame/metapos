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

    public function cashable()
    {
        return $this->morphTo();
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }
}
