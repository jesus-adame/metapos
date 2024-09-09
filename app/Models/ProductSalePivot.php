<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSalePivot extends Pivot
{
    protected $casts = [
        'price' => 'float',
        'tax' => 'float',
        'quantity' => 'int',
    ];
}
