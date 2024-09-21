<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductPurchasePivot extends Pivot
{
    protected $casts = [
        'quantity' => 'int',
        'price' => 'float',
        'tax' => 'float',
        'tax_rate' => 'float',
        'subtotal' => 'float',
        'line_total' => 'float',
    ];
}
