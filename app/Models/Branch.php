<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'type',
        'is_default'
    ];

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
