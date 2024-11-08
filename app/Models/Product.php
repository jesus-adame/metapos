<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Contracts\Locationable;

class Product extends Model implements Auditable
{
    use HasFactory, AuditingAuditable;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'cost',
        'wholesale_price',
        'image',
        'image_url',
        'unit_type',
        'tax', // Tax rate percentage (16%)
    ];

    protected $casts = [
        'price' => 'float',
        'wholesale_price' => 'float',
        'cost' => 'float',
        'stock' => 'float',
        'tax' => 'float',
    ];

    protected $appends = ['final_price'];

    public function scopeWithStock(Builder $query, ?Locationable $location = null): Builder
    {
        return $query->withSum(['inventories as stock' => function ($query) use ($location) {
            if (!is_null($location)) {
                $query->where('location_id', $location->id);
            }
        }], 'quantity');
    }

    public function scopeWhereLocation(Builder $query, Locationable $location)
    {
        return $query->whereHas('inventories', function ($query) use ($location) {
            $query->where('location_id', $location->id);
        });
    }

    public function getFinalPriceAttribute()
    {
        return round($this->price * (1 + ($this->tax / 100)), 2);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'product_sale')
            ->withPivot('quantity', 'price', 'tax', 'tax_rate', 'subtotal', 'line_total')
            ->withTimestamps()
            ->using(ProductSalePivot::class);
    }
}
