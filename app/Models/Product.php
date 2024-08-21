<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Contracts\Locationable;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'cost',
        'image',
        'image_url',
        'unit_type',
        'tax',
        'has_taxes',
    ];

    protected $casts = [
        'price' => 'float',
        'cost' => 'float',
        'stock' => 'integer',
        'tax' => 'float',
    ];

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

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
