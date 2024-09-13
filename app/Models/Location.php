<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Contracts\Locationable;

class Location extends Model implements Locationable, Auditable
{
    use HasFactory, AuditingAuditable;

    protected $fillable = [
        'name',
        'address',
        'type',
        'is_default',
        'phone_number',
        'email',
        'rfc',
        'currency',
        'timezone',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
