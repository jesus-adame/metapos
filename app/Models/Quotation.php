<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'seller_id',
        'date',
        'total',
        'status',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sale')
            ->withPivot('quantity', 'price', 'tax', 'tax_rate', 'subtotal', 'line_total')
            ->withTimestamps()
            ->using(ProductQuotePivot::class);
    }
}
