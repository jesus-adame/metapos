<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'buyer_id', 'branch_id', 'total', 'status', 'purchase_date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_purchase')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
