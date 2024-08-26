<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\Cashable;

class Purchase extends Model implements Cashable
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'buyer_id',
        'total',
        'status',
        'applicated_at',
        'location_id',
    ];

    public function getClassName(): string
    {
        return 'compra';
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_purchase')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
