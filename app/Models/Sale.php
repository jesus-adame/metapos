<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\Cashable;

class Sale extends Model implements Cashable
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'seller_id',
        'cash_register_id',
        'wholesale_sale',
        'status',
        'change',
        'total',
    ];

    public function getClassName(): string
    {
        return 'venta';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sale')
            ->withPivot('quantity', 'price', 'tax', 'tax_rate', 'subtotal', 'line_total')
            ->withTimestamps()
            ->using(ProductSalePivot::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'cashable');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
