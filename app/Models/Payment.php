<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    const CASH_METHOD = 'cash';

    const CREDIT_CARD_METHOD = 'card';

    const DEBIT_CARD_METHOD = 'card';

    const TRANSFER_METHOD = 'transfer';

    protected $fillable = [
        'payment_method_id',
        'payable_id',
        'payable_type',
        'amount',
        'timezone'
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
