<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryTransaction extends Model
{
    use HasFactory;

    const TYPE_ENTRY = 'entry';

    const TYPE_EXIT = 'exit';

    protected $fillable = [
        'product_id', 'type', 'quantity', 'transaction_date', 'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
