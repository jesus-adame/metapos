<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductQuotePivot extends Pivot
{
    protected $casts = [
        'price' => 'float',
        'tax' => 'float',
        'quantity' => 'float',
    ];
}
