<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSalePivot extends Pivot
{
    protected $casts = [
        'quantity' => 'float',
        'price' => 'float',
        'tax' => 'float',
        'tax_rate' => 'float',
        'subtotal' => 'float',
        'line_total' => 'float',
    ];
}
