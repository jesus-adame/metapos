<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\Locationable;

class Warehouse extends Model implements Locationable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'type',
    ];

    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'location');
    }

    public function purchases()
    {
        return $this->morphMany(Inventory::class, 'location');
    }
}
