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
        'sale_id',
        'purchase_id',
        'method',
        'amount',
        'description',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
